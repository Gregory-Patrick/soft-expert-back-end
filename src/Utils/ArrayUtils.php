<?php

namespace App\Utils;

class ArrayUtils {

    public static function removeUnwantedItems($array) {
        return array_filter($array, function($value) {
            return !is_array($value) && $value !== false;
        });
    }
}

?>