
// getNumberOfImages() - counts images in a given folder

function getNumberOfImages ( $folder, $useThumbSubdir, $markerType,
                             $markerLabel )
{
    $dir = opendir($folder);    // Open directory handle
    $count = 0;

    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$useThumbSubdir) {  // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == 'suffix' && ereg("_$markerLabel\.[^.]+$",$file)
                && validFileType($file)) {
                    continue;
            }
            if ($markerType == 'prefix' && ereg("^$markerLabel\_", $file)) {
                continue;
            }
        }

        // We'll look at this one only if it's a file and it matches our list
        // of approved extensions
        if (is_file("$folder/$file") && validFileType($file)) {
                $count++;
        }
    }

    return $count;

}   // -- End of getNumberOfImages()

