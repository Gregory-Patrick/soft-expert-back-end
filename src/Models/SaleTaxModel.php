<?php 

    namespace App\Models;

    use App\Core\BaseModel;

    /**
     * Classe SaleTaxModel
     *
     * Esta classe representa o modelo para a tabela de impostos sobre vendas no banco de dados.
     */
    class SaleTaxModel extends BaseModel {

        /**
         * @var string O nome da tabela.
         */
        protected $table = 'sale_tax';

        /**
         * Construtor da classe SaleTaxModel.
         *
         * @param \PDO $dbConnection A conexão com o banco de dados.
         */
        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }
?>