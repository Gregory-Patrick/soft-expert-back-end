<?php

    namespace App\Utils;

    /**
     * Classe BiuldParams
     *
     * Esta classe fornece métodos utilitários para construir partes de consultas SQL e relacionamentos de dados.
     */
    class BiuldParams {

        /**
         * Constrói partes de uma consulta SQL de inserção.
         *
         * Este método cria a string de colunas, a string de placeholders e um array de valores
         * para uma consulta SQL de inserção.
         *
         * @param array $params Os parâmetros a serem inseridos na consulta.
         * @return array Um array contendo as strings de colunas, placeholders e os valores.
         */
        public static function buildInsertQueryParts(array $params) {
            $columns = array_keys($params);
            $values = array_values($params);

            $columnsString = implode(', ', $columns);
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));

            return [
                'columnsString' => $columnsString,
                'placeholders' => $placeholders,
                'values' => $values,
            ];
        }

        /**
         * Constrói partes de uma consulta SQL de atualização.
         *
         * Este método cria a string `SET` e um array de valores
         * para uma consulta SQL de atualização.
         *
         * @param array $params Os parâmetros a serem atualizados na consulta.
         * @return array Um array contendo a string `SET` e os valores.
         */
        public static function buildUpdateQueryParts(array $params) {
            $columns = array_keys($params);
            $values = array_values($params);

            $setString = implode(', ', array_map(function($col) {
                return "$col = ?";
            }, $columns));
        
            return [
                'setString' => $setString,
                'values' => $values
            ];
        }

        /**
         * Constrói os relacionamentos de dados para um array de dados.
         *
         * Este método itera sobre os relacionamentos definidos e chama a função fornecida
         * para obter os dados relacionados, construindo os relacionamentos para os dados fornecidos.
         *
         * @param array $data O array de dados original.
         * @param array $relations Os relacionamentos a serem construídos.
         * @param callable $getRelatedData A função a ser chamada para obter os dados relacionados.
         * @return array O array de dados com os relacionamentos construídos.
         */
        public static function buildRelations(array $data, array $relations, callable $getRelatedData) {
            foreach ($relations as $relationName => $relation) {
                if (isset($data[$relation['foreign_key']])) {
                    $relatedData = call_user_func($getRelatedData, $relation, $data[$relation['foreign_key']]);

                    if ($relatedData && isset($relation['relations'])) {
                        $relatedData = self::buildRelations($relatedData, $relation['relations'], $getRelatedData);
                    }
                    $data[$relationName] = $relatedData;
                } else {
                    $data[$relationName] = null;
                }
            }
            return $data;
        }
    }

?>