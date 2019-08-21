<?php
namespace Db\Models;
require_once ROOT . '/db/api/Model.php';
use Db\Api\Model;

class MagazinesAuthors extends Model
{
    public $table = 'magazines_authors';
    public $fields = ['magazine', 'author'];
}
