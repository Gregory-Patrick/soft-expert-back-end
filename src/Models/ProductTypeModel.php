<?php 

    namespace App\Models;

    use App\Core\BaseModel;

    class ProductTypeModel extends BaseModel {
        protected $table = 'product_type';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }

?>