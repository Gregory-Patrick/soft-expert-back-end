<?php 

    namespace App\Core;

    use App\Config\Sql;
    use App\Utils\Response;

    class BaseController {
        protected $db;
        protected $model;
        protected $response;

        public function __construct($model) {
            $this->db = new Sql();
            $this->model = $model;
            $this->response = new Response();
        }

        public function getAll() {
            $data = $this->model->findAll();
            if (!$data) {
                $this->response->simpleResponse(404, [
                    'success' => false,
                    'message' => 'No products were found'
                ]);
            }
            $this->response->objectResponse(200, $data);
        }

        public function getById() {
            $id = $this->response->getItemRequestId();

            $data = $this->model->findById($id);
            if (!$data) {
                $this->response->simpleResponse(404, [
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }
            $this->response->objectResponse(200, $data);
        }

        public function create() {
            $data = $this->response->getDataRequest();
            // if(!isset($data['name']) || !isset($data['price'])) {
            //     $this->response->simpleResponse(400, [
            //         'success' => false,
            //         'message' => 'Mandatory parameters not received, name and price is mandatory'
            //     ]);
            // }

            if ($this->model->save($data)) {
                $this->response->simpleResponse(200, [
                    'success' => true,
                    'message' => 'Product registered successfully'
                ]);
            }

            $this->response->simpleResponse(500, [
                'success' => false,
                'message' => 'Something unexpected happened, try again later'
            ]);
        }

        public function update() {
            $id = $this->response->getItemRequestId();
            $data = $this->response->getDataRequest();

            if(!isset($data['name'])) {
                $data['name'] = $this->model->findById($id)['name'];
            }

            if($this->model->update($id, $data)) {
                $this->response->simpleResponse(200, [
                    'success' => true,
                    'message' => 'Product updated successfully'
                ]);
            }

            $this->response->simpleResponse(500, [
                'success' => false,
                'message' => 'Something unexpected happened, try again later'
            ]);
        }

        public function delete() {
            $id = $this->response->getItemRequestId();

            if ($this->model->findById($id)) {
                $this->model->delete($id);
                
                $this->response->simpleResponse(200, [
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ]);
            }

            $this->response->simpleResponse(404, [
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
    }