<?php

class Product {
    private $conn;
    public $id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $image;
    public $quantity; // Alterando a propriedade para quantity

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO Discos (name, description, price, category, image, quantity) VALUES (:name, :description, :price, :category, :image, :quantity)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':quantity', $this->quantity); // Ligação da quantidade

        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM Discos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readByCategory($category) {
        $query = "SELECT * FROM Discos WHERE category = :category";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM Discos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id) {
        $query = "UPDATE Discos SET name = :name, description = :description, price = :price, category = :category, image = :image, quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':quantity', $this->quantity); // Ligação da quantidade

        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM Discos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function addToCart($id, $quantity) {
        session_start();
    
        // Busca o produto pelo ID
        $product = $this->readOne($id);
        
        // Se o carrinho não existir, inicializa-o
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    
        // Verifica se o produto já está no carrinho
        $productFound = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                // Se o produto já estiver no carrinho, atualiza a quantidade
                $item['quantity'] += $quantity; // Alterando para quantity
                $productFound = true;
                break;
            }
        }
    
        // Se o produto não estiver no carrinho, adiciona-o
        if (!$productFound) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'description' => $product['description'],
                'quantity' => $quantity // Alterando para quantity
            ];
        }
    }
}
?>