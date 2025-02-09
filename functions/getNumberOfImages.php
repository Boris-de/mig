<?php

// getNumberOfImages() - Counts images in a given folder.

function getNumberOfImages ( $unsafe_folder )
{
    global $mig_config;
    
    $markerLabel = $mig_config['markerlabel'];
    
    if (is_dir($unsafe_folder)) {
        $dir = opendir($unsafe_folder);    // Open directory handle
        if ($dir === FALSE) {
            exit("ERROR: failed to open album");
        }
    } else {
        return 0;
    }

    $count = 0;

    // Get hidden item list from mig.cf, fills $mig_config['hidden']
    parseMigCf($unsafe_folder);

    while ($file = readdir($dir)) {
        if ($file == '.' || $file == '..') {
            continue; // skip self and parent
        }

        // Skip over thumbnails
        if (!$mig_config['usethumbsubdir']) {
                                 // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($mig_config['markertype'] == 'suffix' && preg_match("#_$markerLabel\.[^.]+$#",$file)
                && getFileType($file)) {
                    continue;
            }
            if ($mig_config['markertype'] == 'prefix' && preg_match("#^$markerLabel\_#", $file)) {
                continue;
            }

        }

        // Ignore hidden items
        if (!empty($mig_config['hidden'][$file])) {
            continue;
        }

        // We'll look at this one only if it's a file and it matches our list
        // of approved extensions
        if (is_file("$unsafe_folder/$file") && getFileType($file)) {
            ++$count;
        }
    }

    return $count;

}   // -- End of getNumberOfImages()

?>
