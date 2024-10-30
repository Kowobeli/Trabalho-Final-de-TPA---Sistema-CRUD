<?php
class PurchaseController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function createPurchase($user_id, $product_id, $quantity) {
       
        $this->db->beginTransaction();

        try {
         
            $stmt = $this->db->prepare("INSERT INTO sales (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();

       
            $stmt = $this->db->prepare("UPDATE products SET quantity = quantity - :quantity WHERE id = :product_id");
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();

           
            $this->db->commit();
            return true; 
        } catch (Exception $e) {
            
            $this->db->rollBack();
            return false; 
        }
    }
}


?>