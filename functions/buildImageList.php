
// buildImageList() - creates a list of images available

function buildImageList ( $baseURL, $baseDir, $albumDir, $currDir,
                          $albumURLroot, $maxColumns, $maxRows,
                          $markerType, $markerLabel,
                          $directoryList, $suppressImageInfo, $useThumbSubdir,
                          $thumbSubdir, $noThumbs, $thumbExt, $suppressAltTags,
                          $sortType, $hidden, $presorted, $description,
                          $imagePopup, $imagePopType, $commentFilePerImage,
                          $startFrom )
{
    global $mig_config;

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    $row = 0;               // Counters for the table formatting
    $col = 0;

    $maxColumns--;          // Tricks maxColumns into working since it
                            // really starts at 0, not 1.

    $maxRows--;             // same for rows

    // prototype the arrays
    $imagefiles     = array ();
    $filedates      = array ();

    $thumbsInFolder = 0;

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
            // Increase thumb counter
            ++$thumbsInFolder;

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

    // Set up pagination environment
    $max_col = $maxColumns + 1;
    $max_row = $maxRows + 1;
    $firstThumb = $startFrom * $max_col * $max_row;
    // This rounds off any fractional part
    $pages = ceil($thumbsInFolder / ($max_col * $max_row));

    // Handle pagination
    if ($thumbsInFolder > ($max_col * $max_row)) {

        $imageList .= '<tr><td colspan=' . $max_col . ' align="center">'
                    . $thumbsInFolder . $mig_config['lang']['total_images'];

        if ($startFrom) {
            $prevPage = $startFrom - 1;

            $imageList .= '<a href="' . $baseURL
                        . '?pageType=folder&currDir=' . $currDir
                        . '&startFrom=' . $prevPage
                        . '">&laquo;</A>&nbsp;&nbsp;';
        }

        for ($i = 1; $i <= $pages; ++$i) {
            if (floor(($i - 11) / 20) == (($i - 11) / 20)) {
                $imageList .= '<br>';
            }
            if ($i == ($startFrom + 1)) {
                $imageList .= '<b>' . $i . '</b>&nbsp;&nbsp;';
            } else {
                $ib = $i - 1;
                $imageList .= '<a href="' . $baseURL
                            . '?pageType=folder&currDir=' . $currDir
                            . '&startFrom=' . $ib . '">'
                            . $i . '</a>&nbsp;&nbsp;';
            }
        }

        if (($startFrom + 1) < $pages) {
            $nextPage = $startFrom + 1;
            $imageList .= '<a href="' . $baseURL
                        . '?pageType=folder&currDir=' . $currDir
                        . '&startFrom=' . $nextPage . '">&raquo;</A>';
        }

        $imageList .= '</td></tr>';
    }

    $thumbCounter = -1;

    while (list($file,$junk) = each($presorted)) {

        ++$thumbCounter;

        if ($thumbCounter >= $firstThumb && $row <= $maxRows) {

            // Only look at valid image types
            $ext = getFileExtension($file);
            if (eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {

                // If this is a new row, start a new <TR>
                if ($col == 0) {
                    $imageList .= '<tr>';
                }

                $fname = getFileName($file);
                $img = buildImageURL($baseURL, $baseDir, $albumDir, $currDir,
                                     $albumURLroot, $fname, $ext,
                                     $suppressImageInfo, $markerType,
                                     $markerLabel, $useThumbSubdir,
                                     $thumbSubdir, $noThumbs, $thumbExt,
                                     $suppressAltTags, $description,
                                     $imagePopup, $imagePopType,
                                     $commentFilePerImage, $startFrom);
                $imageList .= $img;

                // Keep track of what row and column we are on
                if ($col == $maxColumns) {
                    $imageList .= '</tr>';
                    ++$row;
                    $col = 0;
                } else {
                    ++$col;
                }
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

