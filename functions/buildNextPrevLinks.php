<?

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

    // Next is one more than $ThisImagePos.  Test if that has a value
    // and if it does, consider it "next".
    if ($fList[$ThisImagePos+1]) {
        $next = migURLencode($fList[$ThisImagePos+1]);
    } else {
        $next = 'NA';
    }

    // Previous must always be one less than the current index.  If
    // that has a value, that is.  Unless the current index is "1" in
    // which case we know there is no previous.
    
    if ($ThisImagePos == 1) {
        $prev = 'NA';
    } elseif ($fList[$ThisImagePos-1]) {
        $prev = migURLencode($fList[$ThisImagePos-1]); 
    }

    // URL-encode currDir
    $currDir = migURLencode($currDir);

    // newCurrDir is currDir without the leading "./"
    $newCurrDir = getNewCurrDir($currDir);

    // If there is no previous image, show a greyed-out link
    if ($prev == 'NA') {
        $pLink = '&nbsp;[&nbsp;<font color="#999999">'
               . $mig_config['lang']['previmage']
               . '</font>&nbsp;]&nbsp;';

    // else show a real link
    } else {
        $pLink = '&nbsp;[&nbsp;<a href="' . $mig_config['baseurl']
               . '?pageType=' . $mig_config['pagetype'] . '&amp;currDir=' . $currDir
               . '&amp;image=' . $prev;
        if ($mig_config['startfrom']) {
            $pLink .= '&amp;startFrom=' . $mig_config['startfrom'];
        }
        if ($mig_config['mig_dl']) {
            $pLink .= '&amp;mig_dl=' . $mig_config['mig_dl'];
        }
        $pLink .= '">' . $mig_config['lang']['previmage']
                . '</a>&nbsp;]&nbsp;';
    }

    // If there is no next image, show a greyed-out link
    if ($next == 'NA') {
        $nLink = '&nbsp;[&nbsp;<font color="#999999">'
               . $mig_config['lang']['nextimage']
               . '</font>&nbsp;]&nbsp;';
    // else show a real link
    } else {
        $nLink = '&nbsp;[&nbsp;<a href="' . $mig_config['baseurl']
               . '?pageType=' . $mig_config['pagetype'] . '&amp;currDir=' . $currDir
               . '&amp;image=' . $next;
        if ($mig_config['startfrom']) {
            $nLink .= '&amp;startFrom=' . $mig_config['startfrom'];
        }
        if ($mig_config['mig_dl']) {
            $nLink .= '&amp;mig_dl=' . $mig_config['mig_dl'];
        }
        $nLink .= '">' . $mig_config['lang']['nextimage']
                . '</a>&nbsp;]&nbsp;';
    }

    // Current position in the list
    $currPos = '#' . $ThisImagePos . '&nbsp;of&nbsp;' . $i;

    return array( $nLink, $pLink, $currPos );

}   // -- End of buildNextPrevLinks()

?>