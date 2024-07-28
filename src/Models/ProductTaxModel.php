<?php 

    namespace App\Models;

    use App\Core\BaseModel;
use App\Utils\BiuldParams;
use PDO;

    class ProductTaxModel extends BaseModel {
        protected $table = 'product_tax';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        public function find(int $id) {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id_product_type = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function update(int $id, array $params) {
            $queryParts = BiuldParams::buildUpdateQueryParts($params);
            $query = "UPDATE " . $this->table . " SET " . $queryParts['setString'] . " WHERE id_product_type = ?";
            $stmt = $this->conn->prepare($query);
            foreach($queryParts['values'] as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }
            $stmt->bindValue(count($queryParts['values']) + 1, $id, PDO::PARAM_INT);
            return $stmt->execute();
        }

    }

?>