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
// it as a module anyway.
//

// Munge base reference
$baseHref = ereg_replace('/modules.*$', '', $PHP_SELF);

// Redirect browser
header("Location: http://$SERVER_NAME$baseHref/mig.php");

?>
