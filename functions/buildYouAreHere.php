

// buildYouAreHere() - Build the "You are here" line for the top of each page.

function buildYouAreHere ( $currDir, $image, $omitImageName )
{
    global $mig_config;
    global $mig_dl;

    // Use $workingCopy so we don't trash value of $currDir
    $workingCopy = $currDir;

    // Loop until we get down to just the "."
    while ($workingCopy != ".") {

        // $label is the "last" thing in the path. Strip up to that
        $label = ereg_replace("^.*/", "", $workingCopy);

        // Render underscores as spaces and turn spaces into &nbsp;
        $label = str_replace("_", "&nbsp;", $label);
        $label = str_replace(" ", "&nbsp;", $label);

        // Get a URL-encoded copy of $workingCopy
        $encodedCopy = migURLencode($workingCopy);

        if ($image == "" && $workingCopy == $currDir) {
            $url = "&nbsp;&gt;&nbsp;" . $label;
        } else {
            $url = "&nbsp;&gt;&nbsp;<a href=\"" . $mig_config["baseurl"] . "?currDir="
                 . $encodedCopy;
            if ($mig_dl) {
                $url .= "&amp;mig_dl=" . $mig_dl;
            }
            $url .= "\">" . $label . "</a>";
        }

        // Strip the last piece off of $workingCopy to go to next loop
        $workingCopy = ereg_replace("/[^/]+$", "", $workingCopy);

        // Build up the final path over each loop iteration
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If we're down to "." as our currDir then this is "Main"
    if ($currDir == ".") {
        $url = $mig_config["lang"]["main"];
        $x = $hereString;
        $hereString = $url . $x;

    // Or if we're not, then Main should be a link instead of just text
    } else {
        $url = "<a href=\"" . $mig_config["baseurl"] . "?currDir=" . $workingCopy;
        if ($mig_dl) {
            $url .= "&amp;mig_dl=" . $mig_dl;
        }
        $url .= "\">" . $mig_config["lang"]["main"] . "</a>";
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If there's an image, tack it onto the end of the hereString
    // unless we have $omitImageName set to TRUE
    if ($image != "" && ! $omitImageName) {
        $hereString .= "&nbsp;&gt;&nbsp;" . $image;
    }

    return $hereString;

}   // -- End of buildYouAreHere()

