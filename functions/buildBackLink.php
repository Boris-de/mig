<?

// buildBackLink() - Create a "back one step" link.

function buildBackLink ( $currDir, $type )
{
    global $mig_config;

    // $type notes whether we want a "back" link or "up one level" link.
    if ($type == 'back' or $mig_config['nothumbs']) {
        $label = $mig_config['lang']['up_one'];
    } elseif ($type == 'up') {
        if ($mig_config['pagetype'] == 'large') {
            $label = $mig_config['lang']['largeview'];
        } elseif ($mig_config['pagetype'] == 'image') {
            $label = $mig_config['lang']['thumbview'];
        }
    }

    // don't send a link back if we're a the root of the tree
    if ($currDir == '.') {
        if ($mig_config['homelink'] != '') {

            if ($mig_config['homelabel'] == '') {
                $homeLabel = $mig_config['homelink'];
            } else {
                // Get rid of spaces due to silly formatting in MSIE
                $homeLabel = str_replace(' ', '&nbsp;', $mig_config['homelabel']);
            }

            // Build a link to the "home" page
            $retval  = '&nbsp;[&nbsp;<a href="'
                     . $mig_config['homelink'] . '">'
                     . $mig_config['lang']['backhome'] . '&nbsp;'
                     . $homeLabel . '</a>&nbsp;]&nbsp;';
        } else {
            $retval = '<!-- no backLink in root tree -->';
        }
        return $retval;
    }

    // Trim off the last directory, so we go "back" one.
    $junk = ereg_replace('/[^/]+$', '', $currDir);
    $newCurrDir = migURLencode($junk);

    $retval = '&nbsp;[&nbsp;<a href="'
            . $mig_config['baseurl'] . '?currDir=' . $newCurrDir;
    if ($mig_config['startfrom']) {
        $retval .= '&amp;startFrom=' . $mig_config['startfrom'];
    }
    if ($mig_config['mig_dl']) {
        $retval .= '&amp;mig_dl=' . $mig_config['mig_dl'];
    }
    if ($mig_config['pagetype'] == 'large') {
        $retval .= '&amp;pageType=image&amp;image=' . $mig_config['image'];
    }
    $retval .= '">' . $label . '</a>&nbsp;]&nbsp;';

    return $retval;

}   // -- End of buildBackLink()

?>
