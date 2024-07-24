<?php 

    namespace App\Controllers;

    use App\Core\BaseController;
    use App\Models\TaxModel;

    class TaxController extends BaseController{
        private $taxModel;

        public function __construct() {
            $this->taxModel = new TaxModel($this->db->getConnection());
            parent::__construct($this->taxModel);
        }
    }

?>