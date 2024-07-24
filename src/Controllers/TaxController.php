<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\TaxModel;

    class TaxController extends BaseController{
        private $taxModel;

        public function __construct() {
            $db = new Sql();
            $this->taxModel = new TaxModel($db->getConnection());
            parent::__construct($this->taxModel);
        }
    }

?>