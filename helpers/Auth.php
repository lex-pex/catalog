<?php

namespace Helpers;

require ROOT . '/db/models/User.php';
use Db\Models\User;

class Auth {

    public static function logIn(int $id) {
        $_SESSION['csrf_token'] = md5($id.'_logged_in');
        $_SESSION['auth']['csrf_token'] = md5($id.'_logged_in');
        $_SESSION['auth']['identifier'] = $id;
        unset_old_form_params();
    }

    public static function logOut() {
        unset($_SESSION['auth']);
        unset_old_form_params();
    }

    public static function user() {
        if(isset($_SESSION['auth']['identifier']))
            return User::find($_SESSION['auth']['identifier']);
        return null;
    }

    public static function id() {
        echo isset($_SESSION['auth']['identifier']) ? $_SESSION['auth']['identifier'] : '';
    }

    public static function check() {
        return (isset($_SESSION['auth']['identifier']));
    }
}
