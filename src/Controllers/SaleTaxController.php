<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\SaleTaxModel;

    /**
     * Classe SaleTaxController
     *
     * Esta classe gerencia as operações relacionadas aos impostos de vendas.
     */
    class SaleTaxController extends BaseController {
        
        /**
         * @var SaleTaxModel A instância do modelo de impostos de vendas.
         */
        protected $SaleTaxModel;

        /**
         * Construtor da classe SaleTaxController.
         *
         * Inicializa o modelo de impostos de vendas.
         */
        public function __construct() {
            $this->SaleTaxModel = new SaleTaxModel((new Sql())->getConnection());
            parent::__construct($this->SaleTaxModel);
        }

        /**
         * Obtém as regras de validação para os dados de impostos de vendas.
         *
         * @return array Um array associativo contendo as regras de validação.
         */
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

        /**
         * Salva os dados dos impostos de um produto.
         *
         * Valida os dados antes de salvar no modelo de impostos de vendas.
         *
         * @param array $data Os dados dos impostos do produto.
         * @return bool Retorna true se os dados foram salvos com sucesso, caso contrário, false.
         */
        public function saveTaxProduct(array $data) {
            $errors = $this->validate($data, $this->getValidationRules());

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }

            if (!$this->model->save($data)) {
                return false;
            }

            return true;
        }
    }

?>