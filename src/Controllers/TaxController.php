<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\TaxModel;

    class TaxController extends BaseController {
        protected  $taxModel;

        public function __construct() {
            $this->taxModel = new TaxModel((new Sql())->getConnection());
            parent::__construct($this->taxModel);
        }

        private function getValidationRules() {
            return [
                'id' => ['type' => 'int', 'required' => false],
                'id_product_type' => ['type' => 'int', 'required' => false],
                'pis' => ['type' => 'float', 'required' => false],
                'confins' => ['type' => 'float', 'required' => false],
                'icms' => ['type' => 'float', 'required' => false],
                'ipi' => ['type' => 'float', 'required' => false],
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