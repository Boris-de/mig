<?php

// getImageDescription() - Fetches an image description from the comment file (mig.cf).

function getImageDescription($unsafe_image, $description, $short_desc)
{
    // "Long" description
    $imageDesc = '';
    if (isset($description[$unsafe_image])) {
        $imageDesc = $description[$unsafe_image];
    }

    // "Short" description
    $imageShort = '';
    if (isset($short_desc[$unsafe_image])) {
        $imageShort = $short_desc[$unsafe_image];
    }

    // Return both - let the calling code decide which to use.
    return array ($imageShort, $imageDesc);

}   // -- End of getImageDescription()

?>