
// getNumberOfDirs() - Counts subdirectories in a given folder

function getNumberOfDirs ( $folder, $useThumbSubdir, $markerType,
                           $markerLabel )
{
    if (is_dir($folder)) {
        $dir = opendir($folder);    // Open directory handle
    } else {
        return 0;
    }

    $count = 0;
    while ($file = readdir($dir)) {
        if ($file != '.' && $file != '..' && $file != 'thumbs'
            && is_dir("$folder/$file"))
        {
            ++$count;
        }
    }
    
    return $count;

}   // -- End of getNumberOfDirs()

