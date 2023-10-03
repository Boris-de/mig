<?php

// migURLencode() - Fixes a problem where "/" turns into "%2F" when using rawurlencode().

function migURLencode ( $string )
{
    $new = rawurldecode($string);           // decode first
    $new = rawurlencode($new);              // then encode

    return str_replace('%2F', '/', $new);

}   // -- End of migURLencode()

?>
