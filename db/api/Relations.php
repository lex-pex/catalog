<?php

namespace Db\Api;
use PDO;

trait Relations {

    /**
     * Get the record with related records from linked table
     * @param $table string - the name of linked table
     * @param $id int - identifier of needed record
     * @return null or Model with subset of linked item
     */
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

    /**
     * Get the collection of all records with related records from linked table
     * @param $table string - the name of linked table
     * @param $order bool - sorting vector by id
     * @return null or Model with subset of linked item
     */
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

    /**
     * Get the collection of related records from linked table
     * @param $id int is the identifier of needed record
     * @param $m Model is the Model with
     * linked table, goal_id, related table
     * in the 'related' array 
     */
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

    /** Bulk Operations with Linked Tables, In one query, parsing duplicated rows */

    /**
     * Return All Records with According Related as Array
     * In one query, by means of parsing duplicated rows
     * @param Model $model
     * @param bool $order
     * @return array
     */
    public static function bulkAllWith(Model $model, $order = true) { 
        $className = get_called_class();
        $m = new $className();
        $order = $order ? 'ASC' : 'DESC';

        $related = $m->related;
        $table = $related[0][0];
        $linked_table = $related[0][1]; 
        $goal_id = $related[1][0]; 
        $own_id = $related[1][1]; 

        $aliases = "$table.id AS $table" . "_id, ";
        foreach ($model->fields as $n => $f) {
            if(is_numeric($n)) {
                $aliases .= $table . '.' . $f . ' AS ' . $table . '_' . $f . ', ';
                $linked_keys[$table . '_id'] = 'id';
                $linked_keys[$table . '_' . $f] = $f;
            }
        }
        $aliases = trim($aliases, ', ');
        $q = "SELECT $m->table.*, $aliases " .
             "FROM (($m->table LEFT JOIN $linked_table ON $m->table.id = $linked_table.$own_id) " .
             "LEFT JOIN $table ON $table.id = $linked_table.$goal_id) ORDER BY $m->table.id $order"; 
        $res = self::$db->prepare($q);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        return self::parseDuplicatedRows($res, $className, $table, $linked_keys);
    }

    /**
     * Return Range Records for Pagination with According Related as Array
     * In two query, due to amount offset items and parsing duplicated rows
     * Since not all MySql servers support limit/offset in sub queries,
     * here were used two query in separately.
     * @param Model $model - instance list of which needed
     * @param int $offset - start index of selection
     * @param int $limit - actual amount items of the selection
     * @param bool $order - sorting vector
     * @return array - list of the Models with the linked Models
     */
    public static function bulkChunkWith(Model $model, int $offset, int $limit, bool $order = true) {

        $className = get_called_class();
        $m = new $className();
        $order = $order ? 'ASC' : 'DESC';
        $linked_keys = [];

        $q_ids = 'SELECT id FROM '. $m->table .' ORDER BY id '. $order .' LIMIT '. $limit . ' OFFSET ' . $offset;
        $res = self::$db->prepare($q_ids);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();
        $ids = '';
        while ($row = $res->fetch())
            $ids .= $row['id'] . ', ';
        $ids = trim($ids, ', ');

        $related = $m->related;
        $table = $related[0][0];
        $linked_table = $related[0][1];
        $goal_id = $related[1][0];
        $own_id = $related[1][1];

        $aliases = "$table.id AS $table" . "_id, ";

        foreach ($model->fields as $n => $f) {
            if(is_numeric($n)) {
                $aliases .= $table . '.' . $f . ' AS ' . $table . '_' . $f . ', ';
                $linked_keys[$table . '_id'] = 'id';
                $linked_keys[$table . '_' . $f] = $f;
            }
        }

        $aliases = trim($aliases, ', ');

        $q = "SELECT $m->table.*, $aliases " .
             "FROM (($m->table LEFT JOIN $linked_table ON $m->table.id = $linked_table.$own_id) " .
             "LEFT JOIN $table ON $table.id = $linked_table.$goal_id) WHERE $m->table.id IN ($ids) " .
             "ORDER BY $m->table.id $order";

        $res = self::$db->prepare($q);
        $res->bindParam(':offset', $offset, PDO::PARAM_INT);
        $res->bindParam(':limit', $limit, PDO::PARAM_INT);
        $res->setFetchMode(PDO::FETCH_ASSOC);
        $res->execute();

        return self::parseDuplicatedRows($res, $className, $table, $linked_keys);
    }

    /**
     * Eliminate the duplicated records of linked table
     * @param $res
     * @param $className - name is gotten from function get_called_class()
     * @param $table - related table from $related[0][0]
     * @param $linked_keys - keys of linked table, look like "tableName_id" and "tableName_field"
     * @return array as a Result Set of objects with nested arrays of subsets from linked tables
     */
    private static function parseDuplicatedRows($res, $className, $table, $linked_keys) {
        $rs = [];
        $ids = [];
        $i = -1;
        $item = null;
        $m = new $className();
        while ($row = $res->fetch()) {
            if(!in_array($row['id'], $ids)) {
                $m = new $className(); // Eliminate duplicated records
                $m->id = $row['id'];
                foreach ($m->fields as $n => $f)
                    if(is_numeric($n))
                        $m->fields[$f] = $row[$f];
                foreach ($linked_keys as $k => $v) {
                    $item[$v] = $row[$k];
                }
                $m->fields[$table][] = $item;
                $rs[++$i] = $m;
                $ids[] = $row['id']; 
            } else {
                foreach ($linked_keys as $k => $v) { 
                    $item[$v] = $row[$k];
                }
                $m->fields[$table][] = $item;
            }
        }
        return $rs;
    }
}











