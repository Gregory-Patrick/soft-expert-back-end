<?php

    namespace App\Core;

    use App\Config\Sql;
    use App\Utils\Response;
    use App\Utils\Validate;

    /**
     * Classe BaseController
     *
     * Esta classe fornece métodos básicos para manipulação de dados em um controlador.
     */
    class BaseController {
        
        /**
         * @var Sql A instância da conexão com o banco de dados.
         */
        protected $db;
        
        /**
         * @var mixed A instância do modelo.
         */
        protected $model;
        
        /**
         * @var Validate A instância de validação.
         */
        protected $validate;
        
        /**
         * @var Response A instância de resposta.
         */
        public $response;

        /**
         * Construtor da classe BaseController.
         *
         * @param mixed $model A instância do modelo.
         */
        public function __construct($model) {
            $this->model = $model;
            $this->db = new Sql();
            $this->response = new Response();
            $this->validate = new Validate();
        }

        /**
         * Define uma nova instância de validação.
         *
         * @param Validate $validate A nova instância de validação.
         */
        public function setValidate($validate) {
            $this->validate = $validate;
        }

        /**
         * Define uma nova instância de resposta.
         *
         * @param Response $response A nova instância de resposta.
         */
        public function setResponse($response) {
            $this->response = $response;
        }

        /**
         * Valida os dados fornecidos com base nas regras especificadas.
         *
         * @param array $data Os dados a serem validados.
         * @param array $rules As regras de validação.
         */
        protected function validate(array $data, array $rules) {
            $this->validate->isValid($data, $rules);
        }

        /**
         * Define uma resposta de erro de validação.
         *
         * @param array $errors Os erros de validação.
         */
        protected function errorValidate($errors) {
            $this->response->setObjectResponse(400, $errors);
        }

        /**
         * Obtém todos os registros do modelo.
         */
        public function getAll() {
            $data = $this->model->findAll();
            if (!$data) {
                return $this->response->setSimpleResponse(404, 'Not found');
            }
            $this->response->objectResponse(200, $data);
        }

        /**
         * Obtém um registro pelo seu ID.
         */
        public function getById() {
            $id = $this->response->getItemRequestId();
            $data = $this->model->findById($id);
            if (!$data) {
                return $this->response->setSimpleResponse(404, 'Not found');
            }
            $this->response->objectResponse(200, $data);
        }

        /**
         * Cria um novo registro com os dados fornecidos.
         */
        public function create() {
            $data = $this->response->getDataRequest();
            if ($this->model->save($data)) {
                return $this->response->setSimpleResponse(200, 'Registered successfully');
            }
            $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
        }

        /**
         * Atualiza um registro existente com os dados fornecidos.
         */
        public function update() {
            $id = $this->response->getItemRequestId();
            $data = $this->response->getDataRequest();
            if ($this->model->findById($id)) {
                if ($this->model->update($id, $data)) {
                    return $this->response->setSimpleResponse(200, 'Updated successfully');
                }
            }
            $this->response->setSimpleResponse(404, 'Not found');
        }

        /**
         * Exclui um registro pelo seu ID.
         */
        public function delete() {
            $id = $this->response->getItemRequestId();
            if ($this->model->findById($id)) {
                $this->model->delete($id);
                return $this->response->setSimpleResponse(200, 'Deleted successfully');
            }
            $this->response->setSimpleResponse(404, 'Not found');
        }
    }
?>