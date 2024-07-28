<?php 

    namespace App\Controllers;

    use App\Config\Sql;
    use App\Core\BaseController;
    use App\Models\SaleModel;

    /**
     * Classe SaleController
     *
     * Esta classe gerencia as operações relacionadas às vendas.
     */
    class SaleController extends BaseController {
        
        /**
         * @var SaleModel A instância do modelo de vendas.
         */
        protected $saleModel;

        /**
         * Construtor da classe SaleController.
         *
         * Inicializa o modelo de vendas.
         */
        public function __construct() {
            $this->saleModel = new SaleModel((new Sql())->getConnection());
            parent::__construct($this->saleModel);
        }

        /**
         * Obtém as regras de validação para os dados de vendas.
         *
         * @return array Um array associativo contendo as regras de validação.
         */
        private function getValidationRules() {
            return [
                'id_product' => ['type' => 'int', 'required' => true],
                'quantity' => ['type' => 'int', 'required' => true],
                'price_uni' => ['type' => 'float', 'required' => true],
                'price_total' => ['type' => 'float', 'required' => true]
            ];
        }

        /**
         * Cria uma nova venda.
         *
         * Valida os dados antes de criar a venda no modelo e salva as informações de impostos associadas.
         *
         * @return void
         */
        public function create() {
            $data = $this->response->getDataRequest();
            $SaleTaxController = new SaleTaxController();

            if (!isset($data['products']) || !is_array($data['products'])) {
                $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
                return;
            }
            
            foreach ($data['products'] as $product) {
                $saleData = [
                    'id_product' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price_uni' => $product['price'],
                    'price_total' => $product['price'] * $product['quantity']
                ];
                
                $errors = $this->validate($saleData, $this->getValidationRules());
                if (!empty($errors)) {
                    return $this->errorValidate($errors);
                }
                
                $saleId = $this->model->save($saleData);
                if (!$saleId) {
                    $this->response->setSimpleResponse(500, 'Something unexpected happened. Try again later');
                    return;
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
                    return;
                }
            }
            
            $this->response->setSimpleResponse(200, 'Registered successfully');
        }
    }

?>
