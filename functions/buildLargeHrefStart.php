<?php

// buildLargeHrefStart() - Builds the text used for template keyword "largeHrefStart".

function buildLargeHrefStart ( $currDir )
{
    global $mig_config;

    $newCurrDir = migURLencode($currDir);

    $retval = '<a href="' . $mig_config['baseurl'] . '?currDir='
            . $newCurrDir . '&amp;pageType=large&amp;image=' . $mig_config['enc_image'];
    if ($mig_config['startfrom']) {
        $retval .= '&amp;startFrom=' . $mig_config['startfrom'];
    }
    if ($mig_config['mig_dl']) {
        $retval .= '&amp;mig_dl=' . $mig_config['mig_dl'];
    }
    $retval .= '">';

    return $retval;

}   // -- End of buildLargeHrefStart()

?>