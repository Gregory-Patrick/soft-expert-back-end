<?php 
    namespace App\Core;

use App\Utils\BiuldParams;
use PDO;

    class BaseModel {
        protected $conn;
        protected $table;

        public function __construct($dbConnection, $table) {
            $this->conn = $dbConnection;
            $this->table = $table;
        }

        public function findAll() {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function findById(int $id) {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function save(array $params) {
            $queryParts = BiuldParams::buildInsertQueryParts($params);
            $query = "INSERT INTO ".$this->table." (".$queryParts['columnsString'].") VALUES (".$queryParts['placeholders'].")";
            
            $stmt = $this->conn->prepare($query);
            foreach($queryParts['values'] as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }
            return $stmt->execute();
        }

        public function update(int $id, array $params) {
            $queryParts = BiuldParams::buildUpdateQueryParts($params);
            $query = "UPDATE ".$this->table." SET ".$queryParts['setString']." WHERE id = ?";
            
            $stmt = $this->conn->prepare($query);
            foreach($queryParts['values'] as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }
            $stmt->bindValue(count($queryParts['values']) + 1, $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

        public function delete(int $id) {    
            $query = "DELETE FROM ".$this->table." WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }
    }
?>