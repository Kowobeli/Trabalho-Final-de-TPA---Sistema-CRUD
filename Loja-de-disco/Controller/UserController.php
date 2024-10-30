<?php
// app/controllers/AuthController.php
require_once '../Conexao/database.php';
require_once '../Model/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->user = new User($this->db);
    }

    public function register($name, $password, $role) {
        $this->user->name = $name;
        $this->user->password = $password;
        $this->user->role = $role;
        return $this->user->create();
    }

    public function login($name, $password) {
        return $this->user->login($name, $password);
    }
}
?>