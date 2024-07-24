<?php

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductModel;

    class ProductController extends BaseController {
        private $productModel;

        public function __construct() {
            $db = new Sql();
            $this->productModel = new ProductModel($db->getConnection());
            parent::__construct($this->productModel);
        }
    }

?>