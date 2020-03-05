<?php

namespace Db\Util;

use PDO;

class Connection {
    public static function getConnection() {
        $params = require(ROOT . '/db/db_params.php');
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']};charset=utf8";
        $db = new PDO($dsn, $params['user'], $params['pass']);
        return $db;
    }
}
