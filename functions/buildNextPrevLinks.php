<?php
// buildNextPrevLinks() - Build links to the "next" and "previous" images.

/**
/* is used by buildNextPrevLinks()
*/
function _greyLink($text)
{
    return '<span class="inactivelink">'.$text.'</span>';
}

function _prevNextLink($text, $enc_currDir, $imgNr)
{
    global $mig_config;

    $link = '<a href="' . $mig_config['baseurl']
        . '?pageType=' . $mig_config['pagetype'] . '&amp;currDir=' . $enc_currDir
        . '&amp;image=' . $imgNr;
    if ($mig_config['startfrom']) {
        $link .= '&amp;startFrom=' . $mig_config['startfrom'];
    }
    if ($mig_config['mig_dl']) {
        $link .= '&amp;mig_dl=' . $mig_config['mig_dl'];
    }
    $link .= '">' . $text . '</a>';

    return $link;
}

// buildNextPrevLinks() - Build links to the "next" and "previous" images.

function buildNextPrevLinks ( $unsafe_currDir, $presorted )
{
    global $mig_config;

    if (is_dir($mig_config['albumdir']."/$unsafe_currDir")) {
        if ($mig_config['pagetype'] == 'large') {
            $dir = opendir($mig_config['albumdir']."/$unsafe_currDir/".$mig_config['largesubdir']);
        } else {
            $dir = opendir($mig_config['albumdir']."/$unsafe_currDir");
        }
    } else {
        exit("ERROR: no such currDir '" . migHtmlSpecialChars($unsafe_currDir) . "'<br>");
    }

    // Gather all files into an array
    $fileList  = array();
    $filedates = array();
    while ($file = readdir($dir)) {
        if ($file == '.' || $file == '..') {
            continue; // skip self and parent
        }

        $markerLabel = $mig_config['markerlabel'];

        // Ignore thumbnails
        if ($mig_config['markertype'] == 'prefix' && preg_match("#^${markerLabel}_#", $file)) {
            continue;
        }

        if ($mig_config['markertype'] == 'suffix' && preg_match("#_$markerLabel\.[^.]+$#", $file)
            && getFileType($file)) {
                continue;
        }

        // Only look at valid image formats
        if (! getFileType($file)) {
            continue;
        }

        // Ignore the hidden images
        if (isset($mig_config['hidden'][$file])) {
            continue;
        }

        // Make sure this is a file, not a directory.
        // and make sure it isn't presorted
        $localFilename = $mig_config['albumdir'] . "/$unsafe_currDir/$file";
        if (is_file($localFilename) && ! isset($presorted[$file])) {
            $fileList[$file] = TRUE;
            // Store a date, too, if needed
            if (preg_match('#bydate.*#', $mig_config['sorttype'])) {
                $timestamp = filemtime($localFilename);
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    closedir($dir);

    ksort($fileList);       // sort, so we see sorted results

    if ($mig_config['sorttype'] == 'bydate-ascend') {
        ksort($filedates);
    } elseif ($mig_config['sorttype'] == 'bydate-descend') {
        krsort($filedates);
    }

    // Generated final sorted list
    if (preg_match('#bydate.*#', $mig_config['sorttype'])) {
        // since $filedates is sorted by date, and date is
        // the key, the key is pointless to put in the list now.
        // so we store the value, not the key, in $presorted
        foreach (array_values($filedates) as $file) {
            $presorted[$file] = TRUE;
        }

    } else {
        // however, here we have real data in the key, so we push
        // the key, not the value, into $presorted.
        foreach (array_keys($fileList) as $file) {
            $presorted[$file] = TRUE;
        }
    }

    // Gather all files into an array

    $i = 1;                 // iteration counter, etc

    // Yes, position 0 is garbage.  Makes the math easier later.
    $fList = array ( 'blah' );

    $ThisImagePos = NULL;
    foreach (array_keys($presorted) as $file) {

        // If "this" is the one we're looking for, mark it as such.
        if ($file === $mig_config['unsafe_image']) {
            $ThisImagePos = $i;
        }

        $fList[$i] = $file;     // Stash filename in the array
        ++$i;                   // increment the counter, of course.
    }

    if (!is_int($ThisImagePos)) {
        exit('ABORT: image not found in $presorted');
    }

    --$i;                       // Get rid of the last increment...

    // Next is the next with a valid imageFilenameRegexpr behind $ThisImagePos.
    $tempThisImagePos = $ThisImagePos;
    while(isset($fList[$tempThisImagePos+1])
            && !preg_match($mig_config['imageFilenameRegexpr'], $fList[$tempThisImagePos+1])) {
        ++$tempThisImagePos;
    }
    if (isset($fList[$tempThisImagePos+1])) {
        $next = migURLencode($fList[$tempThisImagePos+1]);
    } else {
        $next = 'NA';
    }

    // Previous is the first image with a valid imageFilenameRegexpr
    // before $ThisImagePos

    $tempThisImagePos = $ThisImagePos;
    while(isset($fList[$tempThisImagePos-1])
            && !preg_match($mig_config['imageFilenameRegexpr'], $fList[$tempThisImagePos-1])) {
        --$tempThisImagePos;
    }

    if ($tempThisImagePos <= 1) {
        $prev = 'NA';
    } elseif (isset($fList[$tempThisImagePos-1])) {
        $prev = migURLencode($fList[$tempThisImagePos-1]);
    } else {
        $prev = 'NA';
    }

    // URL-encode currDir
    $enc_currDir = migURLencode($unsafe_currDir);

    //build the links:

    //first parse the prev/nextFormatStrings...

    $fileinfotable = array ( 'l' => $mig_config['lang']['previmage']
                           );
    $prevtext = replaceString($mig_config['prevformatstring'],$fileinfotable);

    $fileinfotable = array ( 'l' => $mig_config['lang']['nextimage']
                           );
    $nexttext = replaceString($mig_config['nextformatstring'],$fileinfotable);

    // If there is no previous image, show a greyed-out link
    if ($prev == 'NA') {
        $pLink = _greyLink($prevtext);
    } else { // else show a real link
        $pLink = _prevNextLink($prevtext, $enc_currDir, $prev);
    }


    // If there is no next image, show a greyed-out link
    if ($next == 'NA') {
        $nLink = _greyLink($nexttext);
    } else { // else show a real link
        $nLink = _prevNextLink($nexttext, $enc_currDir, $next);
    }

    // Current position in the list
    $currPos = '#' . $ThisImagePos . '&nbsp;of&nbsp;' . $i;

    return array( $nLink, $pLink, $currPos );

}   // -- End of buildNextPrevLinks()

?>