<?php 

    namespace App\Models;

    use App\Core\BaseModel;

    class SaleTaxModel extends BaseModel{
        protected $table = 'sale_tax';

        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }

?>