
// getFileName() - figure out a file's name sans extension.

function getFileName( $file )
{
    // Strip off the non-extension part of the filename
    $fname = ereg_replace('\.[^\.]+$', '', $file);

    return $fname;

}   // -- End of getFileName()
