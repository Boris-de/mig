
// buildLargeHrefStart() - builds the text used for template keyword
//                         largeHrefStart

function buildLargeHrefStart ( $baseURL, $currDir, $image, $startFrom )
{

    $newCurrDir = migURLencode($currDir);

    $retval = '<a href="' . $baseURL . '?currDir='
            . $newCurrDir . '&amp;pageType=large&amp;image=' . $image;
    if ($startFrom) {
        $retval .= '&amp;startFrom=' . $startFrom;
    }
    if ($mig_dl) {
        $retval .= '&amp;mig_dl=' . $mig_dl;
    }
    $retval .= '">';

    return $retval;

}   // -- End of buildLargeHrefStart()

