<?php

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductModel;
    use App\Utils\Response;

    class ProductController extends BaseController {
        protected  $productModel;
        protected  $response;

        public function __construct() {
            $this->response = new Response();
            
            $db = new Sql();
            $this->productModel = new ProductModel($db->getConnection());
            parent::__construct($this->productModel);
        }
    }

?>