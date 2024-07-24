<?php

    namespace App\Models;

    use PDO;

    class ProductModel {
        private $conn;

        public function __construct($dbConnection) {
            $this->conn = $dbConnection;
        }

        public function findAll() {
            $stmt = $this->conn->prepare("SELECT * FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function findById(int $id) {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function createProduct(string $name, float $price) {
            $stmt = $this->conn->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            return $stmt->execute();
        }

        public function updateProduct(int $id, string $name, float $price) {
            $stmt = $this->conn->prepare("UPDATE products SET name = :name, price = :price WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        }

        public function deleteProduct(int $id) {
            $stmt = $this->conn->prepare("DELETE FROM products WHERE id = :id");
            $stmt->bindParam(':id', $id , PDO::PARAM_INT);
            return $stmt->execute();
        }
    }

?>