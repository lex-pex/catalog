<?php

namespace Controllers;
require_once ROOT . '/db/models/Magazine.php';
require_once ROOT . '/helpers/Pager.php';
use Db\Models\Magazine;
use Helpers\Pager;

class IndexController {

    public function index($page = 1) {
//        $items = Magazine::all(false);
        $p = new Pager(new Magazine(), 4, false);
        if(!$pager_list = $p->feed($page)) abort(404);
        $pager = $pager_list['pager'];
        $items = $pager_list['result_set'];
        require_once ROOT . '/view/main/index.php';
    }

    public function showMagazine($id) {
        if(!$item = Magazine::with('authors', $id)) abort(404);
        require_once ROOT . '/view/magazine/show.php';
    }
}