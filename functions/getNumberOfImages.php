<?php

// getNumberOfImages() - counts images in a given folder

function getNumberOfImages ( $folder, $markerType,
                             $markerLabel )
{
    global $mig_config;
    
    if (is_dir($folder)) {
        $dir = opendir($folder);    // Open directory handle
    } else {
        return 0;
    }

    $count = 0;

    // Get hidden item list from mig.cf
    list($hidden, $x) = parseMigCf($folder);

    while ($file = readdir($dir)) {

        // Skip over thumbnails
        if (!$mig_config["usethumbsubdir"]) {
                                 // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == "suffix" && ereg("_$markerLabel\.[^.]+$",$file)
                && getFileType($file)) {
                    continue;
            }
            if ($markerType == "prefix" && ereg("^$markerLabel\_", $file)) {
                continue;
            }

        }

        // Ignore hidden items
        if ($hidden[$file]) {
            continue;
        }

        // We'll look at this one only if it's a file and it matches our list
        // of approved extensions
        if (is_file("$folder/$file") && getFileType($file)) {
                ++$count;
        }
    }

    return $count;

}   // -- End of getNumberOfImages()

?>
