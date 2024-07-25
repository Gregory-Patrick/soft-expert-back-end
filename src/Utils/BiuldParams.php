<?php

    namespace App\Utils;

    class BiuldParams {
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
    }

?>