<?php 

    namespace App\Core;

    use App\Config\Sql;
    use App\Utils\Response;
    use App\Utils\Validate;

    class BaseController {
        protected $db;
        protected $model;
        protected $validate;
        public $response;

        public function __construct($model) {
            $this->model = $model;
            $this->db = new Sql();
            $this->response = new Response();
            $this->validate = new Validate();
        }

        protected function validate(array $data, array $rules) {
            $this->validate->isValid($data, $rules);
        }

        protected function errorValidate($errors) {
            $this->response->setObjectResponse(400, $errors);
        }

        protected function getAll() {
            $data = $this->model->findAll();
            if(!$data) {
                $this->response->setSimpleResponse(404, 'Not found');
            }
            $this->response->objectResponse(200, $data);
        }

        protected function getById() {
            $id = $this->response->getItemRequestId();
            $data = $this->model->findById($id);
            if(!$data) {
                $this->response->setSimpleResponse(404, 'Not found');
            }
            $this->response->objectResponse(200, $data);
        }

        protected function create() {
            $data = $this->response->getDataRequest();
            if($this->model->save($data)) {
                $this->response->setSimpleResponse(200, 'Registered successfully');
            }
            $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
        }

        protected function update() {
            $id = $this->response->getItemRequestId();
            $data = $this->response->getDataRequest();
            if($this->model->findById($id)) {
                if($this->model->update($id, $data)) {
                    $this->response->setSimpleResponse(200, 'Updated successfully');
                }
            }
            $this->response->setSimpleResponse(404, 'Not found');
        }

        protected function delete() {
            $id = $this->response->getItemRequestId();
            if($this->model->findById($id)) {
                $this->model->delete($id);
                $this->response->setSimpleResponse(200, 'Deleted successfully');
            }
            $this->response->setSimpleResponse(404, 'Not found');
        }
    }