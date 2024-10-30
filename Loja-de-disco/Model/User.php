<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $password;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para criar um novo usuário
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, password, role) VALUES (:name, :password, :role)";
        $stmt = $this->conn->prepare($query);
        
        // Hash da senha antes de armazenar
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }

    // Método para ler um usuário pelo ID
    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Retorna o usuário encontrado ou null
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para fazer login do usuário
    public function login($name, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE name = :name LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        // Verifica se o usuário foi encontrado
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // Verifica a senha
            if (password_verify($password, $user['password'])) {
                return $user; // Retorna o usuário se a senha estiver correta
            }
        }

        return false; // Retorna false se as credenciais não forem válidas
    }
}
?>