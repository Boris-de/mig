<?

// getNumberOfDirs() - Counts subdirectories in a given folder.

function getNumberOfDirs ( $folder, $currDir )
{
    global $mig_config;
    
    if (is_dir($folder)) {
        $dir = opendir($folder);    // Open directory handle
    } else {
        return 0;
    }

    $count = 0;

    while ($file = readdir($dir)) {
        // Get hidden item list from mig.cf
        list($mig_config['hidden'], $x) = parseMigCf($folder);

        // Must be a directory, and can't be . or ..
        if ($file != '.' && $file != '..' && is_dir("$folder/$file"))
        {
            // Ignore thumbnail subdirectories if in use
            if ($mig_config['usethumbsubdir']
                && $file == $mig_config['thumbsubdir'])
                continue;

            // And full-size directories too
            if ($mig_config['uselargeimages']
                && $file == $mig_config['largesubdir'])
                continue;

            // Ignore hidden items
            if ($mig_config['hidden'][$file]) {
                continue;
            }

            // Otherwise count it
            ++$count;
        }
    }
    
    return $count;

}   // -- End of getNumberOfDirs()

?>