<?php

    namespace App\Models;

    use App\Core\BaseModel;

    /**
     * Classe ProductModel
     *
     * Esta classe representa o modelo para a tabela de produtos no banco de dados.
     */
    class ProductModel extends BaseModel {
        
        /**
         * @var string O nome da tabela.
         */
        protected $table = 'product';

        /**
         * Construtor da classe ProductModel.
         *
         * @param \PDO $dbConnection A conexão com o banco de dados.
         */
        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        /**
         * @var array As relações com outras tabelas.
         */
        protected $relations = [
            'product_type' => [
                'table' => 'product_type',
                'foreign_key' => 'id_product_type',
                'primary_key' => 'id',
                'relations' => [
                    'product_tax' => [
                        'table' => 'product_tax',
                        'foreign_key' => 'id',
                        'primary_key' => 'id_product_type'
                    ]
                ]
            ]
        ];
    }

?>
