<?php

namespace Controllers;

require_once ROOT . '/db/util/Connection.php';
use Db\Util\Connection;

class Migrations {

    private static $db;

    public function __construct() {
        self::$db = Connection::getConnection();
    }

    public function create_and_seed_all_tables() {
        $this->create_all_tables();
        $this->seed_all_tables();
    }

    private function create_all_tables() {
        $this->create_users_table();
        $this->create_magazines_table();
        $this->create_authors_table();
        $this->create_magazines_authors_table();
    }

    private function seed_all_tables() {
        $this->seed_users_table();
        $this->seed_magazines_table();
        $this->seed_authors_table();
        $this->seed_magazines_authors_table();
    }

    /**
     * Seed Tables for current application
     */
    // Table: authors: name, surname
    private function seed_magazines_authors_table() {
        $t = 'magazines_authors';
        $f = ['magazine', 'author'];
        $ids = 'Table "' . $t . '" Id\'s inserted: ';
        for($j = 1; $j <= 10; $j ++) {
            $i = [$j, $j]; // Author to Journal: A == J
            $ids .= $this->insert($t, $f, $i) . ', ';
            if($j < 10) {
                $i = [$j, $j + 1]; // Author to Journal: A > J
                $ids .= $this->insert($t, $f, $i) . ', ';
            }
            if($j > 1) {
                $i = [$j, $j - 1]; // Author to Journal: A < J
                $ids .= $this->insert($t, $f, $i) . ', ';
            }
        }
        echo trim($ids, ', ') . '<br/>';
    }

    // Table: authors: name, surname
    private function seed_authors_table() {
        $t = 'authors';
        $f = ['name', 'surname', 'created_at'];
        $ids = 'Table "' . $t . '" Id\'s inserted: ';
        for($j = 1; $j <= 10; $j ++) {
            $created_at = date('Y-m-d G:i:s');
            $i = ['Name_' . $j, 'Surname_' . $j, $created_at];
            $ids .= $this->insert($t, $f, $i) . ', ';
        }
        echo trim($ids, ', ') . '<br/>';
    }

    // Table: magazines: name, release_at
    private function seed_magazines_table() {
        $t = 'magazines';
        $f = ['name', 'release_at'];
        $ids = 'Table "' . $t . '" Id\'s inserted: ';
        for($j = 1; $j <= 10; $j ++) {
            $name = 'Stunning ' . $j . ' Journal';
            $d = (time() + (3600 * 24 * (6 + $j)));
            $timestamp = date('Y-m-d G:i:s', $d);
            $i = [$name, $timestamp];
            $ids .= $this->insert($t, $f, $i) . ', ';
        }
        echo trim($ids, ', ') . '<br/>';
    }

    // Table: users: name, email, password
    private function seed_users_table() {
        $t = 'users';
        $f = ['name', 'email', 'password', 'role'];
        $ids = 'Table "' . $t . '" Id\'s inserted: ';
        $ids .= $this->insert($t, $f, ['Admin', 'admin@m.org', 'secret_admin', 'admin']) . ', ';
        for($j = 1; $j <= 10; $j ++) {
            $i = ['User_' . $j, 'user-' . $j . '@m.org', '87654321', 'user'];
            $ids .= $this->insert($t, $f, $i) . ', ';
        }
        echo trim($ids, ', ') . '<br/>';
    }

    private function insert(string $table, array $fields, array $a = []) : int {
        $names = '';
        $wildcards = '';
        foreach ($fields as $n => $f)
            if(is_numeric($n)) {
                $names .= "$f, ";
                $wildcards .= ":$f, ";
            }
        $names = trim($names, ', ');              // field, field ...
        $wildcards = trim($wildcards, ', ');      // :field, :field ...
        $query = "INSERT INTO $table ($names) VALUES ($wildcards)";
        $res = self::$db->prepare($query);
        foreach ($fields as $n => $f)
            if(is_numeric($n))
                $res->bindParam(":$f", $a[$n]);
        if ($res->execute()) {
            return self::$db->lastInsertId();
        }
        return 0;
    }

    /**
     * Create Tables for current application
     */
    // id, name, email, password, description ^, image ^, (timestamps)
    private function create_users_table() {
        $sql =  "CREATE TABLE IF NOT EXISTS users (
                 id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                 name VARCHAR( 70 ) NOT NULL,
                 email VARCHAR( 150 ) NOT NULL,
                 password VARCHAR( 150 ) NOT NULL,
                 description VARCHAR( 250 ) NOT NULL DEFAULT '',
                 image VARCHAR( 250 ) NOT NULL DEFAULT '',
                 role VARCHAR( 250 ) NOT NULL DEFAULT 'user',
                 created_at TIMESTAMP NULL,
                 updated_at TIMESTAMP NULL);";
        $result = self::$db->exec($sql);
        print('Created \'users\' Table: ' . ((gettype($result) === 'integer') ? 'TRUE' : 'FALSE')  . '<br/>');
    }

    // id, name, description ^, image ^, release_at, (timestamps)
    private function create_magazines_table() {

        $sql = "CREATE TABLE IF NOT EXISTS magazines (
                id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR( 100 ) NOT NULL,
                description VARCHAR( 250 ) NOT NULL DEFAULT '',
                image VARCHAR( 250 ) NOT NULL DEFAULT '',
                release_at TIMESTAMP NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL);";
        $result = self::$db->exec($sql);
        print('Created "magazines" Table: ' . ((gettype($result) === 'integer') ? 'TRUE' : 'FALSE')  . '<br/>');
    }

    // id, name, surname, father_name ^, (timestamps)
    private function create_authors_table() {
        $sql =  "CREATE TABLE IF NOT EXISTS authors (
                 id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                 name VARCHAR( 100 ) NOT NULL,
                 surname VARCHAR( 100 ) NOT NULL,
                 father_name VARCHAR( 100 ) NULL,
                 created_at TIMESTAMP NULL,
                 updated_at TIMESTAMP NULL);";
        $result = self::$db->exec($sql);
        print('Created "authors" Table: ' . ((gettype($result) === 'integer') ? 'TRUE' : 'FALSE')  . '<br/>');
    }

    // id, name, surname, father_name ^, (timestamps)
    private function create_magazines_authors_table() {
        $sql =  "CREATE TABLE IF NOT EXISTS magazines_authors (
                 id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
                 magazine INT( 11 ) NOT NULL,
                 author INT( 11 ) NOT NULL,
                 FOREIGN KEY (author) REFERENCES authors(id),
                 FOREIGN KEY (magazine) REFERENCES magazines(id));";
        $result = self::$db->exec($sql);
        print('Created "magazines_authors" Table: ' . ((gettype($result) === 'integer') ? 'TRUE' : 'FALSE')  . '<br/>');
    }
}