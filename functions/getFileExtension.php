

// getFileExtension() - Figure out a file's extension and return it.

function getFileExtension ( $file )
{
    global $mig_config;
    
    // Strip off the extension part of filename
    return ereg_replace("^.*\.", "", $file);

}   // -- End of getFileExtension()

