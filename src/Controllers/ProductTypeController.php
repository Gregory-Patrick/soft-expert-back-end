<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductTypeModel;

    class ProductTypeController extends BaseController {
        private $productTypeModel;

        public function __construct() {
            $db = new Sql();
            $this->productTypeModel = new ProductTypeModel($db->getConnection());
            parent::__construct($this->productTypeModel);
        }
    }

?>