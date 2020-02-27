<?php

namespace Controllers;
require_once ROOT . '/db/models/Magazine.php';
require_once ROOT . '/helpers/Pager.php';
use Db\Models\Magazine;
use Helpers\Pager;

/**
 * Controller of public access content 
 */
class IndexController {

    /**
     * The public page to show All Items (browse) 
     * @param $id is identifier to show item by 
     */
    public function index($page = 1) {
        // $items = Magazine::all(false);
        $p = new Pager(new Magazine(), 4, false);
        if(!$pager_set = $p->feed($page)) abort(404);
        $pager = $pager_set['pager'];
        $items = $pager_set['result_set'];
        require_once ROOT . '/view/main/index.php';
    }

    /**
     * The public page to show item 
     * @param $id is identifier to show item by 
     */
    public function showMagazine($id) {
        if(!$item = Magazine::with('authors', $id)) abort(404);
        require_once ROOT . '/view/magazine/show.php';
    }

}


