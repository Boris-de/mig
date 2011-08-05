<?php

// getImageDescription() - Fetches an image description from the comment file (mig.cf).

function getImageDescription ( $file, $description, $short_desc )
{
    global $mig_config;
    
    $imageDesc = '';

    // "Long" description
    if (isset($description[$file])) {
        $imageDesc = $description[$file];
    } else {
        $imageDesc = NULL;
    }

    // "Short" description
    if (isset($short_desc[$file])) {
        $imageShort = $short_desc[$file];
    } else {
        $imageShort = NULL;
    }

    // Return both - let the calling code decide which to use.
    return array ($imageShort, $imageDesc);

}   // -- End of getImageDescription()

?>