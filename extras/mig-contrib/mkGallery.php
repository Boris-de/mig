<?php

//
// I forget who wrote this... sorry.  A Mig user sent it in.  It should
// allow you to run mkGallery from a web browser.  It assumes you have
// taken care of any permission issues (i.e. letting the web server user
// write to the album directories).
//
// Use this at your own risk.  You should test this on a sample gallery
// before using it on a real album.
//

// URL to use to call myself again
echo '<pre>';
if ($PHP_SELF) {    // if using register_globals
    $baseURL = $PHP_SELF;
    } else {            // otherwise, must be using track_vars
        $baseURL = $HTTP_SERVER_VARS['PHP_SELF'];
    }
    // base directory of installation
    if ($PATH_TRANSLATED) {   // if using register_glolals
        $baseDir = dirname($PATH_TRANSLATED);
    } else {                  // otherwise, must be using track_vars
        $baseDir = dirname($HTTP_SERVER_VARS['PATH_TRANSLATED']);
    }

    $albumDir = $baseDir . '/albums';
    $mkGallery = $baseDir . '/util/mkGallery -rant';

    chdir("$albumDir");
    passthru("$mkGallery");

    echo "</pre><font color=\"#dddddd\">Done</font>";

?>
