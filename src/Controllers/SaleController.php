<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\SaleModel;

    class SaleController extends BaseController {
        protected  $saleModel;

        public function __construct() {
            $this->saleModel = new SaleModel((new Sql())->getConnection());
            parent::__construct($this->saleModel);
        }

        private function getValidationRules() {
            return [
                'id_product' => ['type' => 'int', 'required' => true],
                'quantity' => ['type' => 'int', 'required' => true],
                'price_uni' => ['type' => 'float', 'required' => true],
                'price_total' => ['type' => 'float', 'required' => true]
            ];
        }

        public function create() {
            $data = $this->response->getDataRequest();
            $SaleTaxController = new SaleTaxController();

            if (!isset($data['products']) || !is_array($data['products'])) {
                $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
            }
            
            foreach ($data['products'] as $product) {
                $saleData = [
                    'id_product' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price_uni' => $product['price'],
                    'price_total' => $product['price'] * $product['quantity']
                ];
                
                $errors = $this->validate($data, $this->getValidationRules());
                if (!empty($errors)) {
                    return $this->errorValidate($errors);
                }
                
                $saleId = $this->model->save($saleData);
                if (!$saleId) {
                    $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
                }
                
                $taxData = [
                    'id_sale' => $saleId,
                    'pis' => $product['product_type']['product_tax']['pis'],
                    'confins' => $product['product_type']['product_tax']['confins'],
                    'icms' => $product['product_type']['product_tax']['icms'],
                    'ipi' => $product['product_type']['product_tax']['ipi'],
                    'total' => $product['product_type']['product_tax']['pis'] + 
                    $product['product_type']['product_tax']['confins'] + 
                    $product['product_type']['product_tax']['icms'] + 
                    $product['product_type']['product_tax']['ipi']
                ];
                
                if (!$SaleTaxController->saveTaxProduct($taxData)) {
                    $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
                }
            }
            
            return $this->response->setSimpleResponse(200, 'Registered successfully');
        }
    }

?>