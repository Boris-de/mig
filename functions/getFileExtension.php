
// getFileExtension() - figure out a file's extension and return it.

function getFileExtension ( $file )
{

    // Strip off the extension part of the filename
    $ext = ereg_replace('^.*\.', '', $file);

    return $ext;

}   // -- End of getFileExtension()

