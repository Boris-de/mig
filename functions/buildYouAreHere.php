<?php

// buildYouAreHere() - Build the "You are here" line for the top of each page.

function buildYouAreHere ( $currDir )
{
    global $mig_config;

    $hereString = '';

    // Use $workingCopy so we don't trash value of $currDir
    $workingCopy = $currDir;

    // Loop until we get down to just the "."
    while ($workingCopy != '.') {

        // $label is the "last" thing in the path. Strip up to that
        $label = preg_replace('#^.*/#', '', $workingCopy);

        // Render underscores as spaces and turn spaces into &nbsp;
        $label = str_replace('_', '&nbsp;', $label);
        $label = str_replace(' ', '&nbsp;', $label);

        // Get a URL-encoded copy of $workingCopy
        $encodedCopy = migURLencode($workingCopy);

        if ($mig_config['image'] == '' && $workingCopy == $currDir) {
            $url = '&nbsp;&gt;&nbsp;' . $label;
        } else {
            $url = '&nbsp;&gt;&nbsp;<a href="' . $mig_config['baseurl'] . '?currDir='
                 . $encodedCopy;
            if ($mig_config['mig_dl']) {
                $url .= '&amp;mig_dl=' . $mig_config['mig_dl'];
            }
            $url .= '">' . $label . '</a>';
        }

        // Strip the last piece off of $workingCopy to go to next loop
        $workingCopy = preg_replace('#/[^/]+$#', '', $workingCopy);

        // Build up the final path over each loop iteration
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If we're down to "." as our currDir then this is "Main"
    if ($currDir == '.') {
        $url = $mig_config['lang']['main'];
        $x = $hereString;
        $hereString = $url . $x;

    // Or if we're not, then Main should be a link instead of just text
    } else {
        $url = '<a href="' . $mig_config['baseurl'] . '?currDir=' . $workingCopy;
        if ($mig_config['mig_dl']) {
            $url .= '&amp;mig_dl=' . $mig_config['mig_dl'];
        }
        $url .= '">' . $mig_config['lang']['main'] . '</a>';
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If there's an image, tack it onto the end of the hereString
    // unless we have $omitImageName set to TRUE
    if ($mig_config['image'] != '' && ! $mig_config['omitimagename']) {
        $hereString .= '&nbsp;&gt;&nbsp;' . $mig_config['image'];
    }

    return $hereString;

}   // -- End of buildYouAreHere()

?>