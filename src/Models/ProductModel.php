<?php

    namespace App\Models;

    use App\Core\BaseModel;

    class ProductModel extends BaseModel {
        private $table = 'product';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }

        // public function findAll() {
        //     $stmt = $this->conn->prepare("SELECT * FROM product");
        //     $stmt->execute();
        //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
        // }

        // public function findById(int $id) {
        //     $stmt = $this->conn->prepare("SELECT * FROM product WHERE id = :id");
        //     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        //     $stmt->execute();
        //     return $stmt->fetch(PDO::FETCH_ASSOC);
        // }

        // public function createProduct(string $name, string $price) {
        //     $stmt = $this->conn->prepare("INSERT INTO product (name, price) VALUES (:name, :price)");
        //     $stmt->bindParam(':name', $name);
        //     $stmt->bindParam(':price', $price);
        //     return $stmt->execute();
        // }

        // public function updateProduct(int $id, string $name, string $price) {
        //     $stmt = $this->conn->prepare("UPDATE product SET name = :name, price = :price WHERE id = :id");
        //     $stmt->bindParam(':name', $name);
        //     $stmt->bindParam(':price', $price);
        //     $stmt->bindParam(':id', $id);
        //     return $stmt->execute();
        // }

        // public function deleteProduct(int $id) {
        //     $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
        //     $stmt->bindParam(':id', $id , PDO::PARAM_INT);
        //     return $stmt->execute();
        // }

?>