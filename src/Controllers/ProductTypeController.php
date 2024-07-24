<?php 

    namespace App\Controllers;

    use App\Core\BaseController;
    use App\Models\ProductTypeModel;

    class ProductTypeController extends BaseController{
        private $productTypeModel;

        public function __construct() {
            $this->productTypeModel = new ProductTypeModel($this->db->getConnection());
            parent::__construct($this->productTypeModel);
        }
    }

?>