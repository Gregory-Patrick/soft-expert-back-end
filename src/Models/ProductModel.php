<?php

    namespace App\Models;

    use App\Core\BaseModel;

    class ProductModel extends BaseModel {
        protected  $table = 'product';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        protected $relations = [
            'product_type' => [
                'table' => 'product_type',
                'foreign_key' => 'id_product_type',
                'primary_key' => 'id',
                'relations' => [
                    'product_tax' => [
                        'table' => 'product_tax',
                        'foreign_key' => 'id_product_type',
                        'primary_key' => 'id_product_type'
                    ]
                ]
            ]
        ];

    }

?>