
// getFileName() - figure out a file's name sans extension.

function getFileName ( $file )
{
    // Strip off the non-extension part of the filename
    return ereg_replace('\.[^\.]+$', '', $file);

}   // -- End of getFileName()

