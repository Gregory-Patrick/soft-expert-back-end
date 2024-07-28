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

        public function setValidate($validate) {
            $this->validate = $validate;
        }

        public function setResponse($response) {
            $this->response = $response;
        }

        protected function validate(array $data, array $rules) {
            $this->validate->isValid($data, $rules);
        }

        protected function errorValidate($errors) {
            $this->response->setObjectResponse(400, $errors);
        }

        public function getAll() {
            $data = $this->model->findAll();
            if (!$data) {
                return $this->response->setSimpleResponse(404, 'Not found');
            }
            $this->response->objectResponse(200, $data);
        }

        public function getById() {
            $id = $this->response->getItemRequestId();
            $data = $this->model->findById($id);
            if (!$data) {
                return $this->response->setSimpleResponse(404, 'Not found');
            }
            $this->response->objectResponse(200, $data);
        }

        public function create() {
            $data = $this->response->getDataRequest();
            if ($this->model->save($data)) {
                return $this->response->setSimpleResponse(200, 'Registered successfully');
            }
            $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
        }

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