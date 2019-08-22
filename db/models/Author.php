<?php
namespace Db\Models;
require_once ROOT . '/db/api/Model.php';
use Db\Api\Model;

class Author extends Model
{
    public $table = 'authors';
    public $fields = ['name', 'surname', 'father_name', 'created_at', 'updated_at'];

    public $related = [['magazines', 'magazines_authors'], ['magazine', 'author']];
}
