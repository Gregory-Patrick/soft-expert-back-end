<?php

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductModel;

    class ProductController extends BaseController {
        protected  $productModel;

        public function __construct() {
            $this->productModel = new ProductModel((new Sql())->getConnection());
            parent::__construct($this->productModel);
        }

        private function getValidationRules() {
            return [
                'id' => ['type' => 'int', 'required' => false],
                'id_product_type' => ['type' => 'int', 'required' => false],
                'name' => ['type' => 'string', 'required' => false],
                'price' => ['type' => 'int', 'required' => false]
            ];
        }

        public function getById() {
            $errors = $this->validate(
                ['id' => $this->response->getItemRequestId()], 
                $this->getValidationRules()
            );

            if(!empty($errors)){
                return $this->errorValidate($errors);
            }
            parent::getById();
        }

        public function create() {
            $data = $this->response->getDataRequest();
            $errors = $this->validate($data, $this->getValidationRules());

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }
            parent::create();
        }

        public function update() {
            $data = $this->response->getDataRequest();
            $data['id'] = $this->response->getItemRequestId();
            $errors = $this->validate($data, $this->getValidationRules());

            if(!empty($errors)){
                return $this->errorValidate($errors);
            }
            parent::update();
        }

        public function delete() {
            $errors = $this->validate(
                ['id' => $this->response->getItemRequestId()], 
                $this->getValidationRules()
            );

            if(!empty($errors)){
                return $this->errorValidate($errors);
            }
            parent::delete();
        }
    }

?>