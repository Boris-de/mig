<?php

// buildLargeHrefStart() - Builds the text used for template keyword largeHrefStart.

function buildLargeHrefStart ( $currDir, $image, $startFrom )
{
    global $mig_config;

    $newCurrDir = migURLencode($currDir);

    $retval = "<a href=\"" . $mig_config["baseurl"] . "?currDir="
            . $newCurrDir . "&amp;pageType=large&amp;image=" . $image;
    if ($startFrom) {
        $retval .= "&amp;startFrom=" . $startFrom;
    }
    if ($mig_dl) {
        $retval .= "&amp;mig_dl=" . $mig_dl;
    }
    $retval .= "\">";

    return $retval;

}   // -- End of buildLargeHrefStart()

?>
