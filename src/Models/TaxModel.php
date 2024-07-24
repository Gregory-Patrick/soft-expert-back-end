<?php 

    namespace App\Models;

    use App\Core\BaseModel;

    class TaxModel extends BaseModel{
        private $table = 'product_tax';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }

?>