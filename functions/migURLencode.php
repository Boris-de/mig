<?php

// migURLencode() - fixes a problem where "/" turns into "%2F" when
//                  using rawurlencode()

function migURLencode ( $string )
{

    $new = rawurldecode($string);   		// decode first
    $new = rawurlencode($new);      		// then encode
    $new = str_replace("%2F", "/", $new);       // slash (/)

    return $new;

}   // -- End of migURLencode()

?>
