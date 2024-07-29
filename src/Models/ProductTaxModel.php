<?php

    namespace App\Models;

    use PDO;
    use Exception;
    use App\Core\BaseModel;
    use App\Utils\BiuldParams;

    /**
     * Classe ProductTaxModel
     *
     * Esta classe representa o modelo para a tabela de impostos de produtos no banco de dados.
     */
    class ProductTaxModel extends BaseModel {
        
        /**
         * @var string O nome da tabela.
         */
        protected $table = 'product_tax';

        /**
         * Construtor da classe ProductTaxModel.
         *
         * @param \PDO $dbConnection A conexão com o banco de dados.
         */
        public function __construct($dbConnection) {
            parent::__construct($dbConnection, $this->table);
        }

        /**
         * Encontra um registro na tabela `product_tax` pelo ID do tipo de produto.
         *
         * @param int $id O ID do tipo de produto.
         * @return array|false O registro encontrado, ou false se nenhum registro foi encontrado.
         */
        public function find(int $id) {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id_product_type = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                return false;
            }
        }

        /**
         * Atualiza um registro na tabela `product_tax` pelo ID do tipo de produto.
         *
         * @param int $id O ID do tipo de produto.
         * @param array $params Os parâmetros a serem atualizados.
         * @return bool True se a atualização foi bem-sucedida, false caso contrário.
         */
        public function update(int $id, array $params) {
            try {
                $this->conn->beginTransaction();

                $queryParts = BiuldParams::buildUpdateQueryParts($params);
                $query = "UPDATE " . $this->table . " SET " . $queryParts['setString'] . " WHERE id_product_type = ?";
                $stmt = $this->conn->prepare($query);
                foreach($queryParts['values'] as $index => $value) {
                    $stmt->bindValue($index + 1, $value);
                }
                $stmt->bindValue(count($queryParts['values']) + 1, $id, PDO::PARAM_INT);
                $result = $stmt->execute();

                $this->conn->commit();
                return $result;
            } catch (Exception $e) {
                $this->conn->rollBack();
                return false;
            }
        }
    }

?>
