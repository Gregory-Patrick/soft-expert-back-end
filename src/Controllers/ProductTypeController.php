<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\ProductTypeModel;

    /**
     * Classe ProductTypeController
     *
     * Esta classe gerencia as operações relacionadas aos tipos de produtos.
     */
    class ProductTypeController extends BaseController {
        
        /**
         * @var ProductTypeModel A instância do modelo de tipos de produtos.
         */
        protected $productTypeModel;

        /**
         * Construtor da classe ProductTypeController.
         *
         * Inicializa o modelo de tipos de produtos.
         */
        public function __construct() {
            $this->productTypeModel = new ProductTypeModel((new Sql())->getConnection());
            parent::__construct($this->productTypeModel);
        }

        /**
         * Obtém as regras de validação para os dados de tipos de produtos.
         *
         * @return array Um array associativo contendo as regras de validação.
         */
        private function getValidationRules() {
            return [
                'id' => ['type' => 'int', 'required' => false],
                'id_product' => ['type' => 'int', 'required' => false],
                'name' => ['type' => 'string', 'required' => false],
            ];
        }

        /**
         * Obtém um tipo de produto pelo ID.
         *
         * Valida o ID antes de buscar os dados no modelo.
         *
         * @return void
         */
        public function getById() {
            $errors = $this->validate(
                ['id' => $this->response->getItemRequestId()], 
                $this->getValidationRules()
            );

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }
            parent::getById();
        }

        /**
         * Cria um novo tipo de produto.
         *
         * Valida os dados antes de criar o tipo de produto no modelo.
         *
         * @return void
         */
        public function create() {
            $data = $this->response->getDataRequest();
            $errors = $this->validate($data, $this->getValidationRules());

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }
            parent::create();
        }

        /**
         * Atualiza um tipo de produto existente.
         *
         * Valida os dados antes de atualizar o tipo de produto no modelo.
         *
         * @return void
         */
        public function update() {
            $data = $this->response->getDataRequest();
            $data['id'] = $this->response->getItemRequestId();
            $errors = $this->validate($data, $this->getValidationRules());

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }
            parent::update();
        }

        /**
         * Deleta um tipo de produto.
         *
         * Valida o ID antes de deletar o tipo de produto no modelo.
         *
         * @return void
         */
        public function delete() {
            $errors = $this->validate(
                ['id' => $this->response->getItemRequestId()], 
                $this->getValidationRules()
            );

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }
            parent::delete();
        }

        /**
         * Atualiza um tipo de produto por dados do produto.
         *
         * Valida os dados antes de atualizar o tipo de produto no modelo.
         *
         * @param array $data Os dados do produto para atualização.
         * @return bool Retorna true se a atualização for bem-sucedida, false caso contrário.
         */
        public function updateByProduct(array $data) {
            $errors = $this->validate($data, $this->getValidationRules());
            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }

            if ($this->model->update($data['id'], $data)) {
                return true;
            }

            $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
            return false;
        }
    }

?>