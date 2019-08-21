<?php
namespace Controllers;

require_once ROOT . '/db/models/User.php';
require_once ROOT . '/helpers/Auth.php';
use Db\Models\User;
use Helpers\Auth;
use Router;


class UserController {

    public function login() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if(Auth::check()) header('Location: /cabinet');
            require_once ROOT . '/view/user/login.php';
            return;
        }
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $errors = false;
        if($e = $this->validateEmail($email))
            $errors[] = $e;
        if ($e = $this->validatePassword($pass))
            $errors[] = $e;
        $user = $this->validateLoginEmail($email);
        if($user !== null) {
            if ($e = $this->validateLoginPassword($user, $pass))
                $errors[] = $e;
        } else {
            $errors[] = 'This E-mail does not exist';
        }
        if($errors) {
            include_once ROOT.'/view/user/login.php';
            return;
        }
        Auth::logIn($user->id);
        header('Location: /cabinet');
        return;
    }

    public function logout() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') Router::response_404();
        Auth::logOut();
        header('Location: /');
    }

    /* ---------- crud operations --------- */

    public function cabinet($id = null) {
        if(!$user = Auth::user()) header('Location: /login');
        if($id !== null) {
            if($user->role === 'admin')
                $user = User::find($id);
            else Router::response_404();
        }
        require_once ROOT . '/view/user/cabinet.php';
    }

    public function list() {
        if(!admin()) abort(404);
        $list = User::all();
        require_once ROOT . '/view/user/list.php';
    }

    public function create() {
        require_once ROOT . '/view/user/create.php';
    }

    public function store() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') Router::response_404();
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $description = $_POST['description'];
        $errors = false;
        if($e = $this->validateName($name))
            $errors[] = $e;
        if($e = $this->validateEmail($email))
            $errors[] = $e;
        if($e = $this->validateRegisterEmail($email))
            $errors[] = $e;
        if ($e = $this->validatePassword($password))
            $errors[] = $e;
        if ($e = $this->validateDescription($description))
            $errors[] = $e;
        if($errors) {
            include_once ROOT.'/view/user/create.php';
            return;
        } else {
            $user = new User([$name, $email, $password, $description]);
            $user->save();
            Auth::logIn($user->id);
            header('Location: /cabinet');
            return;
        }
    }

    public function edit($id) {
        if(!$user = Auth::user()) header('Location: /login');
        if($user->id != $id)
            if ($user->role === 'admin')
                $user = ($user->id === $id) ? $user : User::find($id);
            else Router::response_404();
        $_SESSION['user_email_before_update'] = $user->email;
        require_once ROOT . '/view/user/edit.php';
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') Router::response_404();
        if(!is_token()) redirect('login');
        if(!$user = Auth::user()) redirect('login');
        $id = $_POST['id'];
        $admin = false;
        if($user->id != $id)
            if($user->role === 'admin'){
                $user = ($user->id == $id) ? $user : User::find($id);
                $admin = true;
            }
            else Router::response_404();
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $description = $_POST['description'];
        $errors = false;
        if($e = $this->validateName($name))
            $errors[] = $e;
        if($e = $this->validateEmail($email))
            $errors[] = $e;
        if($e = $this->validateUpdateEmail($email))
            $errors[] = $e;
        if ($e = $this->validateUpdatePassword($password))
            $errors[] = $e;
        if ($e = $this->validateDescription($description))
            $errors[] = $e;
        if($errors) {
            include_once ROOT.'/view/user/edit.php';
            return;
        } else {
            $user->name = $name;
            $user->email = $email;
            if($password)
                $user->password = $password;
            $user->description = $description;
            $user->save();
            unset_old_form_params();
            redirect('/cabinet' . ($admin ? ('/' . $id) : ''));
        }
    }

    public function destroy() {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') abort(404);
        if(!is_token()) redirect('login');
        $id = $_POST['id'];
        if(!$user = Auth::user()) redirect('login');
        if($user->id != $id) {
            if ($user->role === 'admin') {
                $user = ($user->id == $id) ? $user : User::find($id);
                $user->delete();
                redirect('/user/list');
            }
            else {
                redirect('/');
            }
        }
        $user->delete();
        Auth::logOut();
        redirect('/');
    }

    /* ---------- Validations ---------- */

    // Name Validation
    private function validateName(string $title) : string {
        if(strlen($title) < 3 || strlen($title) > 50)
            return 'Name has to be more than 3 character and less 50';
        return '';
    }

    // Description Validation
    private function validateDescription(string $text) : string {
        if(empty($text)) return '';
        if(strlen($text) < 50 || strlen($text) > 250)
            return 'Status has to be more than 50 character and less 250';
        return '';
    }

    // Email Validation
    private function validateEmail(string $email) : string {
        $pattern = '~^[a-zA-Z0-9.-]+[a-zA-Z0-9-]+@[a-zA-Z0-9.]+$~';
        if(!preg_match($pattern, $email))
            return 'Email has to be real';
        return '';
    }

    // Email in DB validation on register
    private function validateRegisterEmail(string $email) {
        if(User::exists('email', $email))
                return 'This email already exists';
        return '';
    }

    // Email in DB validation on login. Return User or NULL
    private function validateLoginEmail(string $email) {
        $result = User::where('email', $email);
        if (count($result))
            return $result[0];
        return null;
    }

    // Email in DB validation on register
    private function validateUpdateEmail(string $email) {
        if($_SESSION['user_email_before_update'] === $email)
            return '';
        if(User::exists('email', $email))
            return 'This email already exists';
    }

    // Password Validation as word
    private function validatePassword(string $pass) {
        if(strlen($pass) < 5 || strlen($pass) > 30)
            return 'Password has to be from 5 to 30 characters';
        return '';
    }

    // Password Validation on Update as word
    private function validateUpdatePassword(string $pass) {
        if($pass === '') return '';
        if(strlen($pass) < 5 || strlen($pass) > 30)
            return 'Password has to be from 5 to 30 characters';
        return '';
    }

    // Password Validation on Login
    private function validateLoginPassword(User $user, string $pass) {
        if($user->password !== $pass)
            return 'Password not correct';
        return '';
    }

}