

// getImageDescription() - Fetches an image description from the comment file (mig.cf).

function getImageDescription ( $description, $short_desc )
{
    global $mig_config;
    
    $imageDesc = '';

    // "Long" description
    if ($description[$mig_config['image']]) {
        $imageDesc = $description[$mig_config['image']];
    }

    // "Short" description
    if ($short_desc[$mig_config['image']]) {
        $imageShort = $short_desc[$mig_config['image']];
    }

    // Return both - let the calling code decide which to use.
    return array ($imageShort, $imageDesc);

}   // -- End of getImageDescription()

