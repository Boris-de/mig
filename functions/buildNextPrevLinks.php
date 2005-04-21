<?
// buildNextPrevLinks() - Build links to the "next" and "previous" images.

/**
/* is used by buildNextPrevLinks()
*/
function _greyLink($text)
{
    return '<span class="inactivelink">'.$text.'</span>';
}

function _prevNextLink($text,$currDir,$imgNr)
{
    global $mig_config;

        $link = '<a href="' . $mig_config['baseurl']
               . '?pageType=' . $mig_config['pagetype'] . '&amp;currDir=' . $currDir
               . '&amp;image=' . $imgNr;
        if ($mig_config['startfrom']) {
            $link .= '&amp;startFrom=' . $mig_config['startfrom'];
        }
        if ($mig_config['mig_dl']) {
            $link .= '&amp;mig_dl=' . $mig_config['mig_dl'];
        }
        $link .= '">' .$text. '</a>';

    return $link;
}

// buildNextPrevLinks() - Build links to the "next" and "previous" images.

function buildNextPrevLinks ( $currDir, $presorted )
{
    global $mig_config;

    // newCurrDir is currDir without the leading "./"
    $newCurrDir = getNewCurrDir($currDir);

    if (is_dir($mig_config['albumdir']."/$currDir")) {
        if ($mig_config['pagetype'] == 'large') {
            $dir = opendir($mig_config['albumdir']."/$currDir/".$mig_config['largesubdir']);
        } else {
            $dir = opendir($mig_config['albumdir']."/$currDir");
        }
    } else {
        print "ERROR: no such currDir '$currDir'<br>";
        exit;
    }

    // Gather all files into an array
    $fileList = array ();
    while ($file = readdir($dir)) {

        $markerLabel = $mig_config['markerlabel'];

        // Ignore thumbnails
        if ($mig_config['markertype'] == 'prefix' && ereg("^$markerLabel\_", $file)) {
            continue;
        }

        if ($mig_config['markertype'] == 'suffix' && ereg("_$markerLabel\.[^.]+$", $file)
            && getFileType($file)) {
                continue;
        }

        // Only look at valid image formats
        if (! getFileType($file)) {
            continue;
        }

        // Ignore the hidden images
        if ($mig_config['hidden'][$file]) {
            continue;
        }

        // Make sure this is a file, not a directory.
        // and make sure it isn't presorted
        if (is_file($mig_config['albumdir']."/$currDir/$file") && ! $presorted[$file]) {
            $fileList[$file] = TRUE;
            // Store a date, too, if needed
            if (ereg('bydate.*', $mig_config['sorttype'])) {
                $timestamp = filemtime($mig_config['albumdir']."/$currDir/$file");
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    closedir($dir);

    ksort($fileList);       // sort, so we see sorted results
    reset($fileList);       // reset array pointer

    if ($mig_config['sorttype'] == 'bydate-ascend') {
        ksort($filedates);
        reset($filedates);

    } elseif ($mig_config['sorttype'] == 'bydate-descend') {
        krsort($filedates);
        reset($filedates);
    }

    // Generated final sorted list
    if (ereg('bydate.*', $mig_config['sorttype'])) {
        // since $filedates is sorted by date, and date is
        // the key, the key is pointless to put in the list now.
        // so we store the value, not the key, in $presorted
        while (list($junk,$file) = each($filedates)) {
            $presorted[$file] = TRUE;
        }

    } else {
        // however, here we have real data in the key, so we push
        // the key, not the value, into $presorted.
        while (list($file,$junk) = each($fileList)) {
            $presorted[$file] = TRUE;
        }
    }

    reset($presorted);      // reset array pointer

    // Gather all files into an array

    $i = 1;                 // iteration counter, etc

    // Yes, position 0 is garbage.  Makes the math easier later.
    $fList = array ( 'blah' );

    while (list($file, $junk) = each($presorted)) {

        // If "this" is the one we're looking for, mark it as such.
        if ($file == $mig_config['image']) {
            $ThisImagePos = $i;
        }

        $fList[$i] = $file;     // Stash filename in the array
        ++$i;                   // increment the counter, of course.
    }
    reset($fList);

    --$i;                       // Get rid of the last increment...

    // Next is the next with a valid imageFilenameRegexpr behind $ThisImagePos.
    $tempThisImagePos = $ThisImagePos;
    while(isset($fList[$tempThisImagePos+1])
            && !preg_match($mig_config['imageFilenameRegexpr'], $fList[$tempThisImagePos+1])) {
        ++$tempThisImagePos;
    }
    if ($fList[$tempThisImagePos+1]) {
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
    if ($tempThisImagePos == 1) {
        $prev = 'NA';
    } elseif ($fList[$tempThisImagePos-1]) {
        $prev = migURLencode($fList[$tempThisImagePos-1]);
    }

    // URL-encode currDir
    $currDir = migURLencode($currDir);

    // newCurrDir is currDir without the leading "./"
    $newCurrDir = getNewCurrDir($currDir);

    //build the links:

    //first parse the prev/nextFormatStrings...

    $fileinfotable = array ( 'l' => $mig_config['lang']['previmage']
                           );
    $prevtext = replaceString($mig_config['prevformatstring'],$fileinfotable);

    $fileinfotable = array ( 'l' => $mig_config['lang']['nextimage']
                           );
    $nexttext = replaceString($mig_config['nextformatstring'],$fileinfotable);

    // If there is no previous image, show a greyed-out link
    if ($prev == 'NA') $pLink = _greyLink($prevtext);
    // else show a real link
    else $pLink = _prevNextLink($prevtext,$currDir,$prev);


    // If there is no next image, show a greyed-out link
    if ($next == 'NA') $nLink = _greyLink($nexttext);
    // else show a real link
    else $nLink = _prevNextLink($nexttext,$currDir,$next);

    // Current position in the list
    $currPos = '#' . $ThisImagePos . '&nbsp;of&nbsp;' . $i;

    return array( $nLink, $pLink, $currPos );

}   // -- End of buildNextPrevLinks()

?>