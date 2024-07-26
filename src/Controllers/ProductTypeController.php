<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductTypeModel;

    class ProductTypeController extends BaseController {
        protected  $productTypeModel;

        public function __construct() {
            $this->productTypeModel = new ProductTypeModel((new Sql())->getConnection());
            parent::__construct($this->productTypeModel);
        }

        private function getValidationRules() {
            return [
                'id' => ['type' => 'int', 'required' => false],
                'id_product' => ['type' => 'int', 'required' => false],
                'name' => ['type' => 'string', 'required' => false],
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

        public function updateByProduct(array $data) {
            $errors = $this->validate($data, $this->getValidationRules());
            if(!empty($errors)){
                return $this->errorValidate($errors);
            }

            if($this->model->update($data['id'], $data)) {
                return true;
            }

            $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
        }
    }

?>