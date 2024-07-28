<?php

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Utils\ArrayUtils;
    use App\Core\BaseController;
    use App\Models\ProductModel;
    use App\Controllers\ProductTypeController;

    /**
     * Classe ProductController
     *
     * Esta classe gerencia as operações CRUD (Create, Read, Update, Delete) para os produtos.
     */
    class ProductController extends BaseController {
        
        /**
         * @var ProductModel A instância do modelo de produto.
         */
        protected $productModel;

        /**
         * @var ProductTypeController A instância do controlador de tipo de produto.
         */
        protected $productTypeController;

        /**
         * Construtor da classe ProductController.
         *
         * Inicializa o modelo de produto e o controlador de tipo de produto.
         */
        public function __construct() {
            $this->productTypeController = new ProductTypeController();
            $this->productModel = new ProductModel((new Sql())->getConnection());
            parent::__construct($this->productModel);
        }

        /**
         * Obtém as regras de validação para os dados do produto.
         *
         * @return array Um array associativo contendo as regras de validação.
         */
        private function getValidationRules() {
            return [
                'id' => ['type' => 'int', 'required' => false],
                'id_product_type' => ['type' => 'int', 'required' => false],
                'name' => ['type' => 'string', 'required' => false],
                'price' => ['type' => 'int', 'required' => false]
            ];
        }

        /**
         * Obtém um produto pelo seu ID.
         *
         * Valida o ID do produto antes de chamar o método getById da classe base.
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
         * Cria um novo produto.
         *
         * Valida os dados do produto antes de chamar o método create da classe base.
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
         * Atualiza um produto existente.
         *
         * Valida os dados do produto e atualiza o tipo de produto, se necessário, antes de chamar o método update.
         *
         * @return void
         */
        public function update() {
            $data = $this->response->getDataRequest();
            $data['id'] = $this->response->getItemRequestId();
            $errors = $this->validate($data, $this->getValidationRules());

            if (isset($data['product_type'])) {
                if (!$this->productTypeController->updateByProduct($data['product_type'])) {
                    $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
                    return;
                }
            }

            if (!empty($errors)) {
                return $this->errorValidate($errors);
            }

            if ($this->model->findById($data['id'])) {
                $data = ArrayUtils::removeUnwantedItems($data); 
                if ($this->productModel->update($data['id'], $data)) {
                    $this->response->setSimpleResponse(200, 'Updated successfully');
                    return;
                }
            }
            $this->response->setSimpleResponse(404, 'Not found');
        }

        /**
         * Deleta um produto.
         *
         * Valida o ID do produto antes de chamar o método delete da classe base.
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
    }

?>