<?php
namespace Db\Models;
require_once ROOT . '/db/api/Model.php';
use Db\Api\Model;

class Magazine extends Model
{
    public $table = 'magazines';
    public $fields = ['name', 'description', 'release_at', 'image', 'created_at', 'updated_at'];

    public $related = [['authors', 'magazines_authors'], ['author', 'magazine']];

}
