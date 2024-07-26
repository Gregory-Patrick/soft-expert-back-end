<?php 

    namespace App\Models;

    use App\Core\BaseModel;

    class ProductTypeModel extends BaseModel {
        protected $table = 'product_type';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        protected $relations = [
            'product_tax' => [
                'table' => 'product_tax',
                'foreign_key' => 'id_product_type',
                'primary_key' => 'id',
                'relations' => []
            ]
        ];
    }

?>