
// buildBackLink() - spits out a "back one section" link

function buildBackLink ( $baseURL, $currDir, $type, $homeLink, $homeLabel,
                         $noThumbs, $startFrom, $pageType, $image )
{

    global $mig_config;
    global $mig_dl;

    // $type notes whether we want a "back" link or "up one level" link.
    if ($type == 'back' or $noThumbs) {
        $label = $mig_config['lang']['up_one'];
    } elseif ($type == 'up') {
        if ($pageType == 'large') {
            $label = $mig_config['lang']['largeview'];
        } elseif ($pageType == 'image') {
            $label = $mig_config['lang']['thumbview'];
        }
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
            $retval  = '&nbsp;[&nbsp;<a href="' . $homeLink . '">'
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
            . $baseURL . '?currDir=' . $newCurrDir;
    if ($startFrom) {
        $retval .= '&amp;startFrom=' . $startFrom;
    }
    if ($mig_dl) {
        $retval .= '&amp;mig_dl=' . $mig_dl;
    }
    if ($pageType == 'image') {
        $retval .= '&amp;pageType=folder&amp;image=' . $image;
    } elseif ($pageType == 'large') {
        $retval .= '&amp;pageType=image&amp;image=' . $image;
    }
    $retval .= '">' . $label . '</a>&nbsp;]&nbsp;';

    return $retval;

}   // -- End of buildBackLink()

