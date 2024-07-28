<?php 

    namespace App\Models;

    use App\Core\BaseModel;

    /**
     * Classe ProductTypeModel
     *
     * Esta classe representa o modelo para a tabela de tipos de produtos no banco de dados.
     */
    class ProductTypeModel extends BaseModel {

        /**
         * @var string O nome da tabela.
         */
        protected $table = 'product_type';

        /**
         * Construtor da classe ProductTypeModel.
         *
         * @param \PDO $dbConnection A conexão com o banco de dados.
         */
        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }
    }

?>