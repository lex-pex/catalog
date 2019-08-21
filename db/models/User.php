<?php
namespace Db\Models;
require_once ROOT . '/db/api/Model.php';
use Db\Api\Model;

class User extends Model {
    public $table = 'users';
    public $fields = ['name', 'email', 'password', 'description', 'image', 'role', 'created_at', 'updated_at'];
}
