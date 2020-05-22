<?php
//mig.php
//just for internal use for development. not meant for public use.
//run the makefile instead
//works fine for me but not with all functions guaranteed.
//
//werner

$version = 'dev';

/** @noinspection PhpUnusedParameterInspection ($mig_config is used when including lang files) */
function include_all_files($dir, &$mig_config = NULL) {
    //includes all php files in a directory...
    foreach (glob($dir . "/*.php") as $filename) {
        require($filename) ;
    }
}

include('main/defaults.php');
include('main/pathConvert.php');
include_all_files('functions');
include_all_files('languages', $mig_config);

require_once('main/body.php');

?>


