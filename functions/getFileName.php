<?php

// getFileName() - Figure out a file's name sans extension.

function getFileName ( $file )
{
    // Strip off the non-extension part of filename
    return preg_replace('#\.[^\.]+$#', '', $file);

}   // -- End of getFileName()

?>