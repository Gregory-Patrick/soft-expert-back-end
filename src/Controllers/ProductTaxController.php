<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductTaxModel;


    class ProductTaxController extends BaseController {
        protected  $ProductTaxModel;

        public function __construct() {
            $this->ProductTaxModel = new ProductTaxModel((new Sql())->getConnection());
            parent::__construct($this->ProductTaxModel);
        }

        private function getValidationRules() {
            return [
                'id_product_type' => ['type' => 'int', 'required' => true],
                'pis' => ['type' => 'float', 'required' => false],
                'confins' => ['type' => 'float', 'required' => false],
                'icms' => ['type' => 'float', 'required' => false],
                'ipi' => ['type' => 'float', 'required' => false],
            ];
        }

        public function getById() {
            $idProductType = ['id_product_type' => $this->response->getItemRequestId()];
            
            $errors = $this->validate($idProductType, $this->getValidationRules());
            if(!empty($errors)){
                return $this->errorValidate($errors);
            }
            
            $data = $this->model->find($idProductType['id_product_type']);

            if(!$data) {
                $this->response->setSimpleResponse(404, 'Not found');
            }

            $this->response->objectResponse(200, $data);
        }

        public function create() {
            $data = $this->response->getDataRequest();

            $errors = $this->validate($data, $this->getValidationRules());
            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }

            if (!$this->model->find($data['id_product_type'])) {
                parent::create();
            } 

            $params = [
                'pis' => $data['pis'],
                'confins' => $data['confins'],
                'icms' => $data['icms'],
                'ipi' => $data['ipi']
            ];

            if (!$this->model->update($data['id_product_type'], $params)) {
                $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
            }

            $this->response->setSimpleResponse(200, 'Registered successfully');
        }

    }

?>