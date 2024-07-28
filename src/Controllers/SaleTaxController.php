<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\SaleTaxModel;

    class SaleTaxController extends BaseController {
        protected  $SaleTaxModel;

        public function __construct() {
            $this->SaleTaxModel = new SaleTaxModel((new Sql())->getConnection());
            parent::__construct($this->SaleTaxModel);
        }

        private function getValidationRules() {
            return [
                'id_sale' => ['type' => 'int', 'required' => true],
                'pis' => ['type' => 'float', 'required' => true],
                'confins' => ['type' => 'float', 'required' => true],
                'icms' => ['type' => 'float', 'required' => true],
                'ipi' => ['type' => 'float', 'required' => true],
                'total' => ['type' => 'float', 'required' => true]
            ];
        }

        public function saveTaxProduct(array $data) {
            $errors = $this->validate($data, $this->getValidationRules());

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }

            if(!$this->model->save($data)) {
                return false;
            }

            return true;
        }
    }

?>