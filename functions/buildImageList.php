
// buildImageList() - creates a list of images available

function buildImageList( $baseURL, $baseDir, $albumDir, $currDir,
                         $albumURLroot, $maxColumns, $directoryList,
                         $markerType, $markerLabel, $suppressImageInfo,
                         $useThumbSubdir, $thumbSubdir, $noThumbs,
                         $thumbExt, $suppressAltTags, $sortType, $hidden,
                         $presorted, $description, $imagePopup,
                         $imagePopType, $commentFilePerImage )
{
    global $mig_language;
    global $mig_messages;

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    $row = 0;               // Counters for the table formatting
    $col = 0;

    $maxColumns--;          // Tricks maxColumns into working since it
                            // really starts at 0, not 1.

    // prototype the arrays
    $imagefiles     = array ();
    $filedates      = array ();

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

        // We'll look at this one only if it's a file, it's not hidden,
        // and it matches our list of approved extensions
        $ext = getFileExtension($file);
        if (is_file("$albumDir/$currDir/$file") and !$hidden[$file]
                        and !$presorted[$file]
                        and eregi('^(jpg|gif|png|jpeg|jpe)$', $ext))
        {
            // Stash file in an array
            $imagefiles[$file] = TRUE;
            // and stash a timestamp as well if needed
            if (ereg("bydate.*", $sortType)) {
                $timestamp = filemtime("$albumDir/$currDir/$file");
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    ksort($imagefiles); // sort, so we get a sorted list to stuff onto the
                        // end of $presorted

    reset($imagefiles); // reset array pointer

    if ($sortType == "bydate-ascend") {
        ksort($filedates);
        reset($filedates);

    } elseif ($sortType == "bydate-descend") {
        krsort($filedates);
        reset($filedates);
    }

    // Join the two sorted lists together into a single list
    if (ereg("bydate.*", $sortType)) {
        while(list($junk,$file) = each($filedates)) {
            $presorted[$file] = TRUE;
        }

    } else {
        while (list($file,$junk) = each($imagefiles)) {
            $presorted[$file] = TRUE;
        }
    }

    reset($presorted);          // reset array pointer

    while (list($file,$junk) = each($presorted)) {

        // Only look at valid image types
        $ext = getFileExtension($file);
        if (eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {

            // If this is a new row, start a new <TR>
            if ($col == 0) {
                $imageList .= '<tr>';
            }

            $fname = getFileName($file);
            $img = buildImageURL($baseURL, $baseDir, $albumDir, $currDir,
                                 $albumURLroot, $fname, $ext, $markerType,
                                 $markerLabel, $suppressImageInfo,
                                 $useThumbSubdir, $thumbSubdir, $noThumbs,
                                 $thumbExt, $suppressAltTags, $description,
                                 $imagePopup, $imagePopType,
                                 $commentFilePerImage);
            $imageList .= $img;

            // Keep track of what row and column we are on
            if ($col == $maxColumns) {
                $imageList .= '</tr>';
                $row++;
                $col = 0;
            } else {
                $col++;
            }
        }
    }

    closedir($dir);

    // If there aren't any images to work with, just say so.
    if ($imageList == '') {
        $imageList = 'NULL';
    } elseif (!eregi('</tr>$', $imageList)) {
        // Stick a </tr> on the end if it isn't there already.
        $imageList .= '</tr>';
    }

    return $imageList;

}   // -- End of buildImageList()

