<?php

// getFileExtension() - Figure out a file's extension and return it.

function getFileExtension ( $file )
{
    global $mig_config;
    
    // Strip off the extension part of filename
    return preg_replace('#^.*\.#', '', $file);

}   // -- End of getFileExtension()

?>