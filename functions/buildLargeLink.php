

// buildLargeLink() - Builds the text used for template keyword "largeLink".

function buildLargeLink( $currDir )
{
    global $mig_config;

    $newCurrDir = migURLencode($currDir);

    $retval = "&nbsp;[&nbsp;<a href=\"" . $mig_config["baseurl"] . "?currDir="
            . $newCurrDir . "&amp;pageType=large&amp;image=" . $mig_config["image"];
    if ($mig_config["startfrom"]) {
        $retval .= "&amp;startFrom=" . $mig_config["startfrom"];
    }
    if ($mig_config["mig_dl"]) {
        $retval .= "&amp;mig_dl=" . $mig_config["mig_dl"];
    }
    $retval .= "\">" . $mig_config["lang"]["largelink"]
             . "</a>&nbsp;]&nbsp;";

    return $retval;

}   // -- End of buildLargeLink()

