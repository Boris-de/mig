<?php

// getNumberOfDirs() - Counts subdirectories in a given folder

function getNumberOfDirs ( $folder, $useThumbSubdir, $thumbSubdir,
                           $markerType, $markerLabel, $useLargeImages,
                           $largeSubdir, $albumDir, $currDir )
{
    if (is_dir($folder)) {
        $dir = opendir($folder);    // Open directory handle
    } else {
        return 0;
    }

    $count = 0;

    while ($file = readdir($dir)) {
        // Get hidden item list from mig.cf
        list($hidden, $x) = parseMigCf($folder, $useThumbSubdir,
                                       $thumbSubdir, $useLargeImages,
                                       $largeSubdir);

        // Must be a directory, and can't be . or ..
        if ($file != '.' && $file != '..' && is_dir("$folder/$file"))
        {
            // Ignore thumbnail subdirectories if in use
            if ($useThumbSubdir && $file == $thumbSubdir)
                continue;

            // And full-size directories too
            if ($useLargeImages && $file == $largeSubdir)
                continue;

            // Ignore hidden items
            if ($hidden[$file]) {
                continue;
            }

            // Otherwise count it
            ++$count;
        }
    }
    
    return $count;

}   // -- End of getNumberOfDirs()

?>
