<?php 

    namespace App\Core;

    use PDO;
    use Exception;
    use App\Utils\BiuldParams;

    /**
     * Classe BaseModel
     *
     * Esta classe fornece métodos básicos para manipulação de dados em um modelo.
     */
    class BaseModel {
        
        /**
         * @var PDO A instância da conexão com o banco de dados.
         */
        protected $conn;

        /**
         * @var string O nome da tabela do modelo.
         */
        protected $table;

        /**
         * @var array As relações do modelo.
         */
        protected $relations = [];

        /**
         * Construtor da classe BaseModel.
         *
         * @param PDO $dbConnection A instância da conexão com o banco de dados.
         * @param string $table O nome da tabela do modelo.
         */
        public function __construct($dbConnection, $table) {
            $this->conn = $dbConnection;
            $this->table = $table;
        }

        /**
         * Obtém todos os registros da tabela.
         *
         * @return array|null Os registros encontrados ou null se nenhum registro for encontrado.
         */
        public function findAll() {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM " . $this->table);
                $stmt->execute();
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!$data) {
                    return null;
                }

                foreach ($data as &$item) {
                    $item = BiuldParams::buildRelations($item, $this->relations, function($relation, $foreignKeyValue) {
                        return $this->getRelatedData($relation, $foreignKeyValue);
                    });
                }

                return $data;
            } catch (Exception $e) {
                return null;
            }
        }

        /**
         * Obtém um registro pelo seu ID.
         *
         * @param int $id O ID do registro.
         * @return array|null O registro encontrado ou null se nenhum registro for encontrado.
         */
        public function findById(int $id) {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                return BiuldParams::buildRelations($data, $this->relations, function($relation, $foreignKeyValue) {
                    return $this->getRelatedData($relation, $foreignKeyValue);
                });
            } catch (Exception $e) {
                return null;
            }
        }

        /**
         * Salva um novo registro na tabela.
         *
         * @param array $params Os parâmetros do registro a ser salvo.
         * @return bool True se o registro foi salvo com sucesso, False caso contrário.
         */
        public function save(array $params) {
            try {
                $this->conn->beginTransaction();

                $queryParts = BiuldParams::buildInsertQueryParts($params);
                $query = "INSERT INTO " . $this->table . " (" . $queryParts['columnsString'] . ") VALUES (" . $queryParts['placeholders'] . ")";
                
                $stmt = $this->conn->prepare($query);
                foreach($queryParts['values'] as $index => $value) {
                    $stmt->bindValue($index + 1, $value);
                }
                $result = $stmt->execute();

                $this->conn->commit();
                return $result;
            } catch (Exception $e) {
                $this->conn->rollBack();
                return false;
            }
        }

        /**
         * Atualiza um registro existente na tabela.
         *
         * @param int $id O ID do registro a ser atualizado.
         * @param array $params Os novos parâmetros do registro.
         * @return bool True se o registro foi atualizado com sucesso, False caso contrário.
         */
        public function update(int $id, array $params) {
            try {
                $this->conn->beginTransaction();

                $queryParts = BiuldParams::buildUpdateQueryParts($params);
                $query = "UPDATE " . $this->table . " SET " . $queryParts['setString'] . " WHERE id = ?";
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

        /**
         * Exclui um registro da tabela pelo seu ID.
         *
         * @param int $id O ID do registro a ser excluído.
         * @return bool True se o registro foi excluído com sucesso, False caso contrário.
         */
        public function delete(int $id) {
            try {
                $this->conn->beginTransaction();

                $query = "DELETE FROM " . $this->table . " WHERE id = :id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $result = $stmt->execute();

                $this->conn->commit();
                return $result;
            } catch (Exception $e) {
                $this->conn->rollBack();
                return false;
            }
        }

        /**
         * Obtém dados relacionados com base nas relações definidas.
         *
         * @param array $relation A definição da relação.
         * @param int $foreignKeyValue O valor da chave estrangeira.
         * @return array|null Os dados relacionados encontrados ou null se nenhum dado for encontrado.
         */
        protected function getRelatedData(array $relation, $foreignKeyValue) {
            try {
                $query = "SELECT * FROM " . $relation['table'] . " WHERE " . $relation['primary_key'] . " = :foreignKey";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':foreignKey', $foreignKeyValue, PDO::PARAM_INT);
                $stmt->execute();

                $relatedData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($relatedData && isset($relation['relations'])) {
                    $relatedData = BiuldParams::buildRelations($relatedData, $relation['relations'], function($subRelation, $subForeignKeyValue) use ($relatedData) {
                        return $this->getRelatedData($subRelation, $relatedData[$subRelation['foreign_key']]);
                    });
                }
                return $relatedData;
            } catch (Exception $e) {
                return null;
            }
        }
}
?>
