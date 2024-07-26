<?php 

    namespace App\Utils;

    class Validate {

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
                        $errors[] = "The field $field must be an integer.";
                    }
                }
            }
            return $errors;
        }
    }

?>