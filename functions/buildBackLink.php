
// buildBackLink() - spits out a "back one section" link

function buildBackLink( $baseURL, $currDir, $type, $homeLink, $homeLabel,
                        $noThumbs )
{

    global $mig_config;

    // $type notes whether we want a "back" link or "up one level" link.
    if ($type == 'back' or $noThumbs) {
        $label = $mig_config['lang']['up_one'];
    } elseif ($type == 'up') {
        $label = $mig_config['lang']['thumbview'];
    }

    // don't send a link back if we're a the root of the tree
    if ($currDir == '.') {
        if ($homeLink != '') {

            if ($homeLabel == '') {
                $homeLabel = $homeLink;
            } else {
                // Get rid of spaces due to silly formatting in MSIE
                $homeLabel = str_replace(' ', '&nbsp;', $homeLabel);
            }

            // Build a link to the "home" page
            $retval  = '<font size="-1">[&nbsp;<a href="'
                     . $homeLink
                     . '">'
                     . $mig_config['lang']['backhome']
                     . '&nbsp;'
                     . $homeLabel
                     . '</a>&nbsp;]</font><br><br>';
        } else {
            $retval = '<br>';
        }
        return $retval;
    }

    // Trim off the last directory, so we go "back" one.
    $junk = ereg_replace('/[^/]+$', '', $currDir);
    $newCurrDir = migURLencode($junk);

    $retval = '<font size="-1">[&nbsp;<a href="'
            . $baseURL . '?currDir=' . $newCurrDir . '">' . $label
            . '</a>&nbsp;]</font><br><br>';

    return $retval;

}   // -- End of buildBackLink()

