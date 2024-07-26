<?php 

    namespace App\Core;

    use App\Utils\BiuldParams;
    use PDO;

    class BaseModel {
        protected $conn;
        protected $table;
        protected $relations = [];

        public function __construct($dbConnection, $table) {
            $this->conn = $dbConnection;
            $this->table = $table;
        }

        public function findAll() {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$data) {
                return null;
            }

            foreach ($data as &$item) {
                $item = BiuldParams::buildRelations($item, $this->relations, function($relation, $foreignKeyValue) {
                    return $this->getRelatedData($relation, $foreignKeyValue);
                });
            }
    
            return $data;
        }

        public function findById(int $id) {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            //Uso de função anonima pois por alguma razão o php não estava entendendo meu callable: [$this, 'getRelatedData']
            return BiuldParams::buildRelations($data, $this->relations, function($relation, $foreignKeyValue) {
                return $this->getRelatedData($relation, $foreignKeyValue);
            });
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
            $query = "UPDATE " . $this->table . " SET " . $queryParts['setString'] . " WHERE id = ?";
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

        protected function getRelatedData(array $relation, $foreignKeyValue) {
            $query = "SELECT * FROM " . $relation['table'] . " WHERE " . $relation['primary_key'] . " = :foreignKey";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':foreignKey', $foreignKeyValue, PDO::PARAM_INT);
            $stmt->execute();

            $relatedData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (isset($relation['relations'])) {
                foreach ($relatedData as &$data) {
                    $data = BiuldParams::buildRelations($data, $relation['relations'], function($relation, $foreignKeyValue) {
                        return $this->getRelatedData($relation, $foreignKeyValue);
                    });
                }
            }

            return $relatedData;
        }
    }
?>