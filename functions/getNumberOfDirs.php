<?php

// getNumberOfDirs() - Counts subdirectories in a given folder

function getNumberOfDirs ( $folder, $useThumbSubdir, $thumbSubdir,
                           $markerType, $markerLabel )
{
    if (is_dir($folder)) {
        $dir = opendir($folder);    // Open directory handle
    } else {
        return 0;
    }

    $count = 0;

    while ($file = readdir($dir)) {
        // Must be a directory, and can't be . or ..
        if ($file != '.' && $file != '..' && is_dir("$folder/$file"))
        {
            // Ignore thumbnail subdirectories if in use
            if ( (! $useThumbSubdir) ||
                 ($useThumbSubdir && $file != $thumbSubdir) )
            {
                ++$count;
            }
        }
    }
    
    return $count;

}   // -- End of getNumberOfDirs()

?>
