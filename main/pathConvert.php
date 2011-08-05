<?php

// -----------------------------------------------------------------
//                    MODIFYING THE INCLUDE PATH
// -----------------------------------------------------------------
//
// This is not normally required, but in some peculiar setups you
// are forced to use non-real paths in order to use the include()
// function.  That is, you have a real path, for instance:
//      /u25/vhost/www12345/www/mig/myfile.php
// but the ISP has PHP installed such that you need to tell include()
// to use this virtual path instead to the same file:
//     /mig/myfile.php
//
// To address this, the following three options exist.  Do not use
// these unless you have to, and know you have to.  If you don't
// need to use them and do anyway, you'll probably break Mig.
//
// $pathConvertFlag
//     This is a boolean to determine if conversion is needed.  Only
//     set this to TRUE if you know you need to do so.
//
// Defaults to FALSE.
//
// Example:
//     $pathConvertFlag = FALSE;
//

$pathConvertFlag = FALSE;

//
// $pathConvertRegex
//     This is a regular expression string, used to tell Mig how to
//     modify your include path.  If you don't know regular expressions,
//     here's probably all you need to know:
//     
//     ^    means "beginning of string"
//     .*   is a wildcard for any number of characters of any kind
//          (note - it will also match 0 characters in some cases)
//          
//     Going back to the earlier example, if you want to start out
//     with this: 
//         /u25/vhost/www12345/www/mig/myfile.php
//     and end up with this:
//         /mig/myfile.php
//         
//     You could define:
//         $pathConvertFlag = TRUE;
//         $pathConvertRegex = "#^.*/www/#";
//         $pathConvertTarget = "/";
//         
//     So the regex would match this:  /u25/vhost/www12345/www/
//     and replace it with a single slash... resulting in:
//         /mig/myfile.php 
//
// Defaults to an empty string.
//         
// Example:
//     $pathConvertRegex = "#^.*/www/#";
//     

$pathConvertRegex = "";

//
// $pathConvertTarget
//     This is the target string, which replaces the portion matched by
//     the regex.  Usually this should be "/", but it can be changed.
//     See the notes for $convertPathRegex (above) for more details.
//
// Defaults to an empty string.
//
// Example:
//     $pathConvertTarget = "/";
//

$pathConvertTarget = "";

// -----------------------------------------------------------------
// End of "INCLUDE PATH" modification section
// -----------------------------------------------------------------


?>
