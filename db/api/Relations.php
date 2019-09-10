<?php

namespace Db\Api;
use PDO;

trait Relations {

    public static function with($table, $id) {
        $className = get_called_class();
        $m = new $className();
        $query = "SELECT * FROM $m->table WHERE id = :id";
        $res = self::$db->prepare($query);
        $res->bindParam(':id', $id, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $row = $res->fetch();
        if($row) {
            $m->id = $row['id'];
            foreach ($m->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $m->fields[$table] = self::getRelated($id, $m);
            return $m;
        }
        return null;
    }

    public static function allWith($table, $order = true) {
        $className = get_called_class();
        $m = new $className();
        $order = $order ? 'asc' : 'desc';
        $query = "SELECT * FROM $m->table ORDER BY id $order";
        $res = self::$db->prepare($query);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($m->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $m->fields[$table] = self::getRelated($row['id'], $m);
            $i++;
        }
        return $resultSet;
    }

    public static function chunkWith(string $table, int $offset, int $limit, bool $order = true) {
        $order = $order ? 'asc' : 'desc';
        $className = get_called_class();
        $m = new $className();
        $query = "SELECT * FROM $m->table ORDER BY id $order LIMIT :limit OFFSET :offset";
        $res = self::$db->prepare($query);
        $res->bindParam(':offset', $offset, PDO::PARAM_INT);
        $res->bindParam(':limit', $limit, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $i = 0;
        $resultSet = [];
        while ($row = $res->fetch()) {
            $m = new $className();
            $m->id = $row['id'];
            foreach ($m->fields as $n => $f)
                if(is_numeric($n))
                    $m->fields[$f] = $row[$f];
            $resultSet[$i] = $m;
            $m->fields[$table] = self::getRelated($row['id'], $m);
            $i++;
        }
        return $resultSet;
    }

    private static function getRelated(int $id, Model $m) {
        // SELECT * FROM authors WHERE id IN (SELECT author FROM magazines_authors WHERE magazine = 1)
        $table = $m->related[0][0];
        $goal_id = $m->related[1][0];
        $r_table = $m->related[0][1];
        $condition_id = $m->related[1][1];
        $q = "SELECT * FROM $table WHERE id IN (SELECT $goal_id FROM $r_table WHERE $condition_id = $id)";
        $res = self::$db->prepare($q);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        return $res->fetchAll();
    }


}