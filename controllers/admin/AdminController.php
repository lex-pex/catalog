<?php

namespace Controllers\Admin;


class AdminController {

    public function __construct() {
        if(!admin()) abort(404);
    }

    public function panel() {
        include_once ROOT . '/view/admin/panel.php';
    }
}

