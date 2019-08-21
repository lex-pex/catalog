<?php

namespace Controllers;

class TestController
{

    public function test($id = '', $name = '') {
        echo 'TestController::test( ' . $id . ' | ' . $name . ' )';
    }

    public function mainTemplate() {
        require_once ROOT . '/view/temp/mainTemplate.php';
    }
}