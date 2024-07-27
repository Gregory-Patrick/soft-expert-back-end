<?php

    namespace App\Models;

    use App\Core\BaseModel;

    class SaleModel extends BaseModel {
        protected  $table = 'sale';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        protected $relations = [

        ];

    }

?>