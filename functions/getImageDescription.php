
// getImageDescription() - Fetches an image description from the
//                         comments file (mig.cf)

function getImageDescription ( $image, $description, $short_desc )
{

    $imageDesc = '';

    // "Long" description
    if ($description[$image]) {
        $imageDesc = $description[$image];
    }

    // "Short" description
    if ($short_desc[$image]) {
        $imageShort = $short_desc[$image];
    }

    // Return both - let the calling code decide which to use.
    return array ($imageShort, $imageDesc);

}   // -- End of getImageDescription()

