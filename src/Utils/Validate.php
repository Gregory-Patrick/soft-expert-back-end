<?php 

    namespace App\Utils;

    /**
     * Classe Validate
     *
     * Esta classe fornece métodos para validação de dados com base em regras fornecidas.
     */
    class Validate {

        /**
         * Valida os dados com base nas regras fornecidas.
         *
         * @param array $data Os dados a serem validados.
         * @param array $rules As regras de validação.
         * @return array Um array de erros de validação, se houver.
         */
        public function isValid($data, $rules) {
            $errors = [];

            foreach ($rules as $field => $rule) {
                if (!isset($data[$field])) {
                    if (isset($rule['required']) && $rule['required']) {
                        $errors[] = "The field $field is required.";
                    }
                    continue;
                }

                if (isset($rule['type'])) {
                    $type = $rule['type'];
                    if ($type === 'int' && !is_int($data[$field])) {
                        $errors[] = "The field $field must be an integer.";
                    } elseif ($type === 'string' && !is_string($data[$field])) {
                        $errors[] = "The field $field must be a string.";
                    } elseif (($type === 'float' && !is_float($data[$field])) || ($type === 'float' && !is_int($data[$field]))) {
                        $errors[] = "The field $field must be a float or integer.";
                    }
                }
            }
            return $errors;
        }
    }

?>