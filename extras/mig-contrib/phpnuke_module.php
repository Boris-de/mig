<?php

//
// This file is for PHP-Nuke users who want to make Mig a module
// on their site.
//
// To use, create a new subdirectory in your modules directory, such
// as "Photo_Gallery".  Then copy this file there and rename it to
// "index.php".
//
// Some readers will probably tell me that this doesn't fit into the
// PHP-Nuke module way of doing things.  Yeah, it probably doesn't.
// But Mig wasn't written to be a module, the PHP-Nuke code was
// written later as a side thing, and you're not obligated to use
// it as a module anyway.  It works as a standalone script too.
// It only has to be a module to be in the menu box, I believe.
//

// Munge base reference
$baseHref = preg_replace('#/modules.*$#', '', $PHP_SELF);

if ($_SERVER['SERVER_NAME']) {
    $SERVER_NAME = $_SERVER['SERVER_NAME'];
} elseif ($HTTP_SERVER_VARS['SERVER_NAME']) {
    $SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
}

if ($SERVER_NAME) {
    // Redirect browser
    header("Location: http://$SERVER_NAME$baseHref/mig.php");
    exit;
} else {
    print 'ERROR: Can't find SERVER_NAME!';
    exit;
}

?>
