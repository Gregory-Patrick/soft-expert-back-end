<?php 

    namespace App\Utils;

    class Validate {
        function isNonNegative($value) {
            return is_numeric($value) && $value >= 0;
        }
    }

?>