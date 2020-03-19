<?php
namespace Controllers\Magazine;
require_once ROOT . '/db/models/Magazine.php';
require_once ROOT . '/db/models/Author.php';
require_once ROOT . '/db/models/MagazinesAuthors.php';
require_once ROOT . '/helpers/FileManager.php';
require_once ROOT . '/helpers/Pager.php';

use Db\Models\Author;
use Db\Models\Magazine;
use Db\Models\MagazinesAuthors;
use Helpers\FileManager;
use Helpers\Pager;

class MagazineController
{
    public function __construct() {
        if(!admin()) abort(404);
    }

    /**
     * Browse all items example
     * Render the page of select all
     * @param int $page number of pagination
     */
    public function list($page = 1) {
        // $list = Magazine::all('authors', false);
        // $list = Magazine::allWith('authors', false);
        // $list = Magazine::bulkAllWith(new Author(), true);
        // $list = Magazine::chunkWith('authors', 0, 5, false);
        // $list = Magazine::bulkChunkWith(new Author(), 0, 5, false);
        // $p = new Pager(new Magazine(), 5, false, 'authors');
        $p = new Pager(new Magazine(), 5, false, new Author()); // Bulk SQL Operation
        if(!$pager_list = $p->feed($page)) abort(404);
        $pager = $pager_list['pager'];
        $list = $pager_list['result_set'];
        require_once ROOT . '/view/magazine/list.php';
    }

    /**
     * Read operation example, render the page of the item
     * @param $id identifier of needed object 
     */
    public function show($id) {
//        $item = Magazine::find($id);
        if(!$item = Magazine::with('authors', $id)) abort(404);
        require_once ROOT . '/view/magazine/show.php';
    }

    public function create() {
        unset_old_form_params();
        $authors = Author::all();
        require_once ROOT . '/view/magazine/create.php';
    }

    public function edit($id) {
        unset_old_form_params();
        if(!$item = Magazine::with('authors', $id)) abort(404);
        $authors = Author::all();
        require_once ROOT . '/view/magazine/edit.php';
    }

    public function store() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!token()) redirect('login');
        $name = $_POST['name'];
        $description = $_POST['description'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $day = $_POST['day'];
        $release_date = $year . '-' . $month . '-' . $day . ' 00:00:00';
        $errors = false;
        $item = null;
        if($e = $this->validateName($name))
            $errors[] = $e;
        if ($e = $this->validateDescription($description))
            $errors[] = $e;
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            if($e = $this->validateImage())
                $errors[] = $e;
            if(!$errors) {
                $item = new Magazine([$name, $description, $release_date]);
                $item->save();
                $m = new FileManager('magazines');
                $m->delDir($item->id);
                $fileName = date('dmyHis');
                $path = $m->upload($item->id, $fileName);
                $item->image = $path;
            }
        }
        if ($errors) {
            include_once ROOT . '/view/magazine/edit.php';
            return;
        } else {
            $item = $item ? $item : new Magazine([$name, $description, $release_date]);
            if(isset($_POST['authors'])) $this->addAuthors($_POST['authors'], $item->id);
        }
        $item->save();
        unset_old_form_params();
        header('Location: /magazine/' . $item->id);
        return;
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        $id = $_POST['id'];
        if(!$item = Magazine::find($id)) abort(404);

        if(isset($_POST['staff'])) $this->deleteAuthors($_POST['staff'], $id);
        if(isset($_POST['authors'])) $this->addAuthors($_POST['authors'], $id);

        $name = $_POST['name'];
        $description = $_POST['description'];
        $year = $_POST['year'];
        $month = $_POST['month'];
        $day = $_POST['day'];
        $release_date = $year . '-' . $month . '-' . $day . ' 00:00:00';
        $delete_image = isset($_POST['delete_image']);
        $errors = false;
        if($e = $this->validateName($name))
            $errors[] = $e;
        if ($e = $this->validateDescription($description))
            $errors[] = $e;
        if($delete_image) {
            $m = new FileManager('magazines');
            $m->delDir($id);
            $item->image = '';
        } elseif (is_uploaded_file($_FILES['image']['tmp_name'])) {
            if($e = $this->validateImage())
                $errors[] = $e;
            if(!$e) {
                $m = new FileManager('magazines');
                $m->delDir($id);
                $fileName = date('dmyHis');
                $path = $m->upload($id, $fileName);
                $item->image = $path;
            }
        }
        if($errors) {
            include_once ROOT . '/view/magazine/edit.php';
            return;
        } else {
            $item->name = $name;
            $item->description = $description;
            $item->release_at = $release_date;
            $item->save();
            unset_old_form_params();
            redirect('magazine/' . $item->id);
        }
    }

    public function destroy() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        if(!admin()) abort(404);
        $id = $_POST['id'];
        if (!$m = Magazine::find($id)) abort(404);
        $fm = new FileManager('magazines');
        $fm->delDir($id);
        $m->delete();
        unset_old_form_params();
        redirect('magazine/list');
    }

    /* ---------- Authors Assignments ---------- */

    public function deleteAuthors(array $ids, $id) {
        $rs = MagazinesAuthors::where('magazine', $id);
        for($i = 0; $i < count($rs); $i++)
            if(in_array($rs[$i]->author, $ids)) $rs[$i]->delete();
    }

    public function addAuthors(array $ids, $id) {
        for($i = 0; $i < count($ids); $i++) {
            $m = new MagazinesAuthors([$id, $ids[$i]]);
            $m->save();
        }
    }

    /* ---------- Validations ---------- */

    // Name Validation
    private function validateName(string $title) : string {
        if(strlen($title) < 3 || strlen($title) > 50)
            return 'Name has to be more than 3 character and less 50';
        return '';
    }

    // Description Validation
    private function validateDescription(string $text) : string {
        if(empty($text)) return '';
        if(strlen($text) < 50 || strlen($text) > 250)
            return 'Status has to be more than 50 character and less 250';
        return '';
    }

    public function validateImage() {
        if ($_FILES["image"]["size"] > 500000)
            return "File may not be larger than 500 Kb";
        return '';
    }


}




