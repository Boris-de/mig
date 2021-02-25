<?php

function migHtmlSpecialChars($str) {
    global $mig_config;
    return htmlspecialchars($str, ENT_QUOTES, $mig_config['charset']);
}

?>