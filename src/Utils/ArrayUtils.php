<?php

    namespace App\Utils;

    /**
     * Classe ArrayUtils
     *
     * Esta classe fornece utilitários para manipulação de arrays.
     */
    class ArrayUtils {

        /**
         * Remove itens indesejados de um array.
         *
         * Este método remove todos os itens de um array que são arrays ou valores booleanos `false`.
         *
         * @param array $array O array a ser filtrado.
         * @return array O array filtrado contendo apenas os itens desejados.
         */
        public static function removeUnwantedItems($array) {
            return array_filter($array, function($value) {
                return !is_array($value) && $value !== false;
            });
        }
    }

?>