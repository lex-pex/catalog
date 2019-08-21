<?php

namespace Controllers;
require_once ROOT . '/db/models/Magazine.php';
use Db\Models\Magazine;

class IndexController {

    public function index() {
        $items = Magazine::all(false);
        require_once ROOT . '/view/main/index.php';
    }

    public function showMagazine($id) {
        if(!$item = Magazine::with('authors', $id)) abort(404);
        require_once ROOT . '/view/magazine/show.php';
    }
}