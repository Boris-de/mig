
// validFileType() - Returns TRUE if filetype is supported by Mig

function validFileType ( $filename )
{
    $ext = getFileExtension($filename);

    // "Image" file types
    if (eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {
        return TRUE;
    }

    // Nothing matched
    return FALSE;

}   // -- End of validFileType()

