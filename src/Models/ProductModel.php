<?php

    namespace App\Models;

    use App\Core\BaseModel;

    class ProductModel extends BaseModel {
        protected  $table = 'product';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }

?>