
// buildYouAreHere() - build the "You are here" line for the top
// of each page

function buildYouAreHere( $baseURL, $currDir, $image )
{
    global $mig_config;

    // Use $workingCopy so we don't trash value of $currDir
    $workingCopy = $currDir;

    // Loop until we get down to just the '.'
    while ($workingCopy != '.') {

        // $label is the "last" thing in the path. Strip up to that
        $label = ereg_replace('^.*/', '', $workingCopy);
        // Render underscores as spaces and turn spaces into &nbsp;
        $label = str_replace('_', '&nbsp;', $label);
        $label = str_replace(' ', '&nbsp;', $label);

        // Get a URL-encoded copy of $workingCopy
        $encodedCopy = migURLencode($workingCopy);

        if ($image == '' and $workingCopy == $currDir) {
            $url = '&nbsp;:&nbsp;<b>' . $label . '</b>';
        } else {
            $url = '&nbsp;:&nbsp;<a href="' . $baseURL . '?currDir='
                 . $encodedCopy . '">' . $label . '</a>';
        }

        // Strip the last piece off of $workingCopy to go to next loop
        $workingCopy = ereg_replace('/[^/]+$', '', $workingCopy);

        // Build up the final path over each loop iteration
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If we're down to '.' as our currDir then this is 'Main'
    if ($currDir == '.') {
        $url = '<b>' . $mig_config['lang']['main'] . '</b>';
        $x = $hereString;
        $hereString = $url . $x;

    // Or if we're not, then Main should be a link instead of just text
    } else {
        $url = '<a href="' . $baseURL . '?currDir=' . $workingCopy
             . '">' . $mig_config['lang']['main'] . '</a>';
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If there's an image, tack it onto the end of the hereString
    if ($image != '') {
        $hereString .= '&nbsp;:&nbsp;<b>' . $image . '</b>';
    }

    $x = $hereString;
    $hereString = '<font size="-1">' . $x . '</font>';
    return $hereString;

}   // -- End of buildYouAreHere()

