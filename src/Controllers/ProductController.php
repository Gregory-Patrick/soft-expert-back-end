<?php

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Utils\ArrayUtils;
    use App\Core\BaseController;
    use App\Models\ProductModel;
    use App\Controllers\ProductTypeController;

    class ProductController extends BaseController {
        protected $productModel;
        protected $productTypeController;

        public function __construct() {
            $this->productTypeController = new ProductTypeController;
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

            if(isset($data['product_type'])) {
                if(!$this->productTypeController->updateByProduct($data['product_type'])) {
                    $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
                }
            }

            if(!empty($errors)){
                return $this->errorValidate($errors);
            }

            if($this->model->findById($data['id'])) {
                $data = ArrayUtils::removeUnwantedItems($data); 
                if($this->productModel->update($data['id'], $data)) {
                    $this->response->setSimpleResponse(200, 'Updated successfully');
                }
            }
            $this->response->setSimpleResponse(404, 'Not found');
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