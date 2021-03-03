<?php

function _get_magic_quotes_gpc() {
    return function_exists('get_magic_quotes_gpc') ? @get_magic_quotes_gpc() : 0;
}

function string_starts_with($haystack, $needle) {
    if (function_exists('str_starts_with')) { // PHP >= 8.0
        return str_starts_with($haystack, $needle);
    }
    return strpos($haystack, $needle) === 0;
}

?>