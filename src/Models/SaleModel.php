<?php

    namespace App\Models;

    use App\Core\BaseModel;
    use App\Utils\BiuldParams;

    /**
     * Classe SaleModel
     *
     * Esta classe representa o modelo para a tabela de vendas no banco de dados.
     */
    class SaleModel extends BaseModel {
        
        /**
         * @var string O nome da tabela.
         */
        protected $table = 'sale';

        /**
         * @var array Relações com outras tabelas.
         */
        protected $relations = [
            'sale_tax' => [
                'table' => 'sale_tax',
                'foreign_key' => 'id',
                'primary_key' => 'id_sale',
                'relations' => []
            ]
        ];

        /**
         * Construtor da classe SaleModel.
         *
         * @param \PDO $dbConnection A conexão com o banco de dados.
         */
        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        /**
         * Salva uma nova venda no banco de dados.
         *
         * @param array $params Os parâmetros para a nova venda.
         * @return mixed O ID da nova venda ou false em caso de falha.
         */
        public function save(array $params) {
            $queryParts = BiuldParams::buildInsertQueryParts($params);
            $query = "INSERT INTO ".$this->table." (".$queryParts['columnsString'].") VALUES (".$queryParts['placeholders'].")";

            $stmt = $this->conn->prepare($query);
            foreach($queryParts['values'] as $index => $value) {
                $stmt->bindValue($index + 1, $value);
            }

            if(!$stmt->execute()) {
                return false;
            }

            return $this->conn->lastInsertId();
        }
    }

?>