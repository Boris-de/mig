<?php

// buildLargeLink() - builds the text used for template keyword largeLink

function buildLargeLink( $currDir, $image, $startFrom )
{
    global $mig_config;
    global $mig_dl;

    $newCurrDir = migURLencode($currDir);

    $retval = "&nbsp;[&nbsp;<a href=\"" . $mig_config["baseurl"] . "?currDir="
            . $newCurrDir . "&amp;pageType=large&amp;image=" . $image;
    if ($startFrom) {
        $retval .= "&amp;startFrom=" . $startFrom;
    }
    if ($mig_dl) {
        $retval .= "&amp;mig_dl=" . $mig_dl;
    }
    $retval .= "\">" . $mig_config["lang"]["largelink"]
             . "</a>&nbsp;]&nbsp;";

    return $retval;

}   // -- End of buildLargeLink()

?>
