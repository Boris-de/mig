<?

// getImageDescription() - Fetches an image description from the comment file (mig.cf).

function getImageDescription ( $file, $description, $short_desc )
{
    global $mig_config;
    
    $imageDesc = '';

    // "Long" description
    if ($description[$file]) {
        $imageDesc = $description[$file];
    }

    // "Short" description
    if ($short_desc[$file]) {
        $imageShort = $short_desc[$file];
    }

    // Return both - let the calling code decide which to use.
    return array ($imageShort, $imageDesc);

}   // -- End of getImageDescription()

?>