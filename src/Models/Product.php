<?php
// src/Models/Product.php

namespace App\Models;

use PDO;
use PDOException;

class Product {
    private $pdo;
    private $table = 'products';

    public $id;
    public $name;
    public $code;
    public $price;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Recupera todos os produtos
    public static function all($pdo) {
        $stmt = $pdo->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Recupera um produto pelo ID
    public static function find($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cria um novo produto
    public function save() {
        if ($this->id) {
            // Atualiza o produto existente
            $stmt = $this->pdo->prepare("UPDATE products SET name = :name, code = :code, price = :price WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        } else {
            // Cria um novo produto
            $stmt = $this->pdo->prepare("INSERT INTO products (name, code, price) VALUES (:name, :code, :price)");
        }
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':price', $this->price);

        return $stmt->execute();
    }

    // Deleta um produto
    public function delete() {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
