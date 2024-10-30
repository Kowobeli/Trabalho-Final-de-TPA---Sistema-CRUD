<?php

require_once '../Conexao/database.php';
require_once '../Model/Disco.php';

class DiscoController {
    private $db;
    private $product;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->product = new Product($this->db);
    }

    public function create($name, $description, $price, $category, $image, $quantity) {
        
        $targetDir = '../uploads/';
        $targetFile = $targetDir . basename($image['name']);

        
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            
            $this->product->name = $name;
            $this->product->description = $description;
            $this->product->price = $price;
            $this->product->category = $category;
            $this->product->image = $targetFile; // Caminho da imagem
            $this->product->quantity = $quantity;

            
            return $this->product->create();
        } else {
            echo "Erro ao fazer upload da imagem.";
            return false;
        }
    }

    public function read() {
        return $this->product->read();
    }

    public function readOne($id) {
        return $this->product->readOne($id);
    }

    public function update($id, $name, $description, $price, $category, $quantity, $image = null) {
        $this->product->id = $id;
    
        if ($image && $image['tmp_name']) {
            $targetDir = '../uploads/';
            $targetFile = $targetDir . basename($image['name']);
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                $this->product->image = $targetFile;
            } else {
                echo "Erro ao fazer upload da imagem.";
                return false;
            }
        }
    
        $this->product->name = $name;
        $this->product->description = $description;
        $this->product->price = $price;
        $this->product->category = $category;
        $this->product->quantity = $quantity;
    
        return $this->product->update($id);
    }
    

    public function delete($id) {
        return $this->product->delete($id);
    }
}
?>