
// getNumberOfImages() - counts images in a given folder

function getNumberOfImages( $folder, $useThumbSubdir, $markerType,
                            $markerLabel )
{

    $dir = opendir($folder);    // Open directory handle

    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$useThumbSubdir) {  // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == 'suffix'
                and eregi("_$markerLabel\.(gif|jpg|png|jpeg|jpe)$", $file)) {
                    continue;
            }
            if ($markerType == 'prefix' and ereg("^$markerLabel\_", $file)) {
                continue;
            }
        }

        // We'll look at this one only if it's a file and it matches our list
        // of approved extensions
        $ext = getFileExtension($file);
        if (is_file("$folder/$file")
            and eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {
                $count++;
        }
    }

    return $count;

}   // -- End of getNumberOfImages()

