
// getFileExtension() - figure out a file's extension and return it.

function getFileExtension ( $file )
{
    // Strip off the extension part of the filename
    return ereg_replace('^.*\.', '', $file);

}   // -- End of getFileExtension()

