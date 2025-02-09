<?php

// buildYouAreHere() - Build the "You are here" line for the top of each page.

function buildYouAreHere ( $unsafe_currDir )
{
    global $mig_config;

    $hereString = '';

    // Use $unsafe_workingCopy so we don't trash value of $unsafe_currDir
    $unsafe_workingCopy = $unsafe_currDir;

    // Loop until we get down to just the "."
    while ($unsafe_workingCopy !== null && $unsafe_workingCopy != '.') {

        // $label is the "last" thing in the path. Strip up to that
        $label = preg_replace('#^.*/#', '', $unsafe_workingCopy);

        // Render underscores as spaces and turn spaces into &nbsp;
        /** @psalm-suppress PossiblyInvalidArgument */
        $label = strtr($label, array('_' => '&nbsp;', ' ' => '&nbsp;'));

        // Get a URL-encoded copy of $unsafe_workingCopy
        $encodedCopy = migURLencode($unsafe_workingCopy);

        if ($mig_config['enc_image'] == '' && $unsafe_workingCopy == $unsafe_currDir) {
            $url = '&nbsp;&gt;&nbsp;' . $label;
        } else {
            $url = '&nbsp;&gt;&nbsp;<a href="' . $mig_config['baseurl'] . '?currDir='
                 . $encodedCopy;
            if ($mig_config['mig_dl']) {
                $url .= '&amp;mig_dl=' . $mig_config['mig_dl'];
            }
            $url .= '">' . $label . '</a>';
        }

        // Strip the last piece off of $unsafe_workingCopy to go to next loop
        $unsafe_workingCopy = preg_replace('#/[^/]+$#', '', $unsafe_workingCopy);

        // Build up the final path over each loop iteration
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If we're down to "." as our currDir then this is "Main"
    if ($unsafe_currDir == '.') {
        $url = $mig_config['lang']['main'];
        $x = $hereString;
        $hereString = $url . $x;

    // Or if we're not, then Main should be a link instead of just text
    } else {
        $url = '<a href="' . $mig_config['baseurl'] . '?currDir=' . $unsafe_workingCopy;
        if ($mig_config['mig_dl']) {
            $url .= '&amp;mig_dl=' . $mig_config['mig_dl'];
        }
        $url .= '">' . $mig_config['lang']['main'] . '</a>';
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If there's an image, tack it onto the end of the hereString
    // unless we have $omitImageName set to TRUE
    if ($mig_config['enc_image'] != '' && ! $mig_config['omitimagename']) {
        $hereString .= '&nbsp;&gt;&nbsp;' . $mig_config['enc_image'];
    }

    return $hereString;

}   // -- End of buildYouAreHere()

?>
