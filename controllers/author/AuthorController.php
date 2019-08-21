<?php

namespace Controllers\Author;
require_once ROOT . '/db/models/Author.php';
use Db\Models\Author;

class AuthorController
{
    public function list() {
        $list = Author::all(false);
        require_once ROOT . '/view/author/list.php';
    }

    public function show($id) {
        $item = Author::find($id);
        require_once ROOT . '/view/author/show.php';
    }

    public function create() {
        unset_old_form_params();
        require_once ROOT . '/view/author/create.php';
    }

    public function edit($id) {
        unset_old_form_params();
        if(!$item = Author::find($id)) abort(404);
        require_once ROOT . '/view/author/edit.php';
    }

    public function store() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $father_name = $_POST['father_name'];
        $errors = false;
        if($e = $this->validateName($name, 'Name'))
            $errors[] = $e;
        if($e = $this->validateName($surname, 'Surname'))
            $errors[] = $e;
        if($father_name != '')
            if($e = $this->validateName($father_name, 'Father Name or empty or'))
                $errors[] = $e;
        if ($errors) {
            include_once ROOT . '/view/author/create.php';
            return;
        }
        $item = new Author([$name, $surname, $father_name]);
        $item->save();
        redirect('author/' . $item->id);
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        $id = $_POST['id'];
        if(!$item = Author::find($id)) abort(404);
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $father_name = $_POST['father_name'];
        $errors = false;
        if($e = $this->validateName($name, 'Name'))
            $errors[] = $e;
        if($e = $this->validateName($surname, 'Surname'))
            $errors[] = $e;
        if($father_name != '')
            if($e = $this->validateName($father_name, 'Father Name or empty or'))
                $errors[] = $e;
        if($errors) {
            include_once ROOT . '/view/author/edit.php';
            return;
        } else {
            $item->name = $name;
            $item->surname = $surname;
            $item->father_name = $father_name;
            $item->save();
            redirect('author/' . $item->id);
        }
    }

    public function destroy() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        if(!admin()) abort(404);
        $id = $_POST['id'];
        if (!$item = Author::find($id)) abort(404);
        $item->delete();
        unset_old_form_params();
        redirect('author/list');
    }

    /* ---------- Validations ---------- */

    // Name Validation
    private function validateName(string $title, string $itemName) : string {
        if(strlen($title) < 3 || strlen($title) > 50)
            return $itemName . ' has to be more 3 and less 50 characters';
        return '';
    }

}





























