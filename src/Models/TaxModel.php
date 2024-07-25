<?php 

    namespace App\Models;

    use App\Core\BaseModel;

    class TaxModel extends BaseModel{
        protected $table = 'product_tax';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }

?>