
// getImageDescription() - Fetches an image description from the
//                         comments file (mig.cf)

function getImageDescription ( $image, $description )
{

    $imageDesc = '';
    if ($description[$image]) {
        $imageDesc = $description[$image];
    }
    return $imageDesc;

}   // -- End of getImageDescription()

