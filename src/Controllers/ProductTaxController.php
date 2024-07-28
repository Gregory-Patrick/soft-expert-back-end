<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductTaxModel;

    /**
     * Classe ProductTaxController
     *
     * Esta classe gerencia as operações relacionadas aos impostos sobre produtos.
     */
    class ProductTaxController extends BaseController {
        
        /**
         * @var ProductTaxModel A instância do modelo de impostos sobre produtos.
         */
        protected $ProductTaxModel;

        /**
         * Construtor da classe ProductTaxController.
         *
         * Inicializa o modelo de impostos sobre produtos.
         */
        public function __construct() {
            $this->ProductTaxModel = new ProductTaxModel((new Sql())->getConnection());
            parent::__construct($this->ProductTaxModel);
        }

        /**
         * Obtém as regras de validação para os dados de impostos sobre produtos.
         *
         * @return array Um array associativo contendo as regras de validação.
         */
        private function getValidationRules() {
            return [
                'id_product_type' => ['type' => 'int', 'required' => true],
                'pis' => ['type' => 'float', 'required' => false],
                'confins' => ['type' => 'float', 'required' => false],
                'icms' => ['type' => 'float', 'required' => false],
                'ipi' => ['type' => 'float', 'required' => false],
            ];
        }

        /**
         * Obtém os impostos de um produto pelo ID do tipo de produto.
         *
         * Valida o ID do tipo de produto antes de buscar os dados no modelo.
         *
         * @return void
         */
        public function getById() {
            $idProductType = ['id_product_type' => $this->response->getItemRequestId()];
            
            $errors = $this->validate($idProductType, $this->getValidationRules());
            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }
            
            $data = $this->model->find($idProductType['id_product_type']);

            if (!$data) {
                $this->response->setSimpleResponse(404, 'Not found');
                return;
            }

            $this->response->objectResponse(200, $data);
        }

        /**
         * Cria ou atualiza os impostos de um produto.
         *
         * Valida os dados antes de criar ou atualizar os impostos no modelo.
         *
         * @return void
         */
        public function create() {
            $data = $this->response->getDataRequest();

            $errors = $this->validate($data, $this->getValidationRules());
            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }

            if (!$this->model->find($data['id_product_type'])) {
                parent::create();
                return;
            } 

            $params = [
                'pis' => $data['pis'],
                'confins' => $data['confins'],
                'icms' => $data['icms'],
                'ipi' => $data['ipi']
            ];

            if (!$this->model->update($data['id_product_type'], $params)) {
                $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
                return;
            }

            $this->response->setSimpleResponse(200, 'Registered successfully');
        }
    }

?>