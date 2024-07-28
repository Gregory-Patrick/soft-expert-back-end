<?php

    namespace App\Models;

    use App\Core\BaseModel;
    use App\Utils\BiuldParams;

    class SaleModel extends BaseModel {
        protected  $table = 'sale';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        protected $relations = [
            'sale_tax' => [
                'table' => 'sale_tax',
                'foreign_key' => 'id',
                'primary_key' => 'id_sale',
                'relations' => []
            ]
        ];

        public function save(array $params) {
            $queryParts = BiuldParams::buildInsertQueryParts($params);
            $query = "INSERT INTO ".$this->table." (".$queryParts['columnsString'].") VALUES (".$queryParts['placeholders'].")";

            $stmt = $this->conn->prepare($query);
            foreach($queryParts['values'] as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }

            if(!$stmt->execute()) {
                return false;
            }

            return $this->conn->lastInsertId();
        }
    }

?>