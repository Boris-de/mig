
// buildImageList() - creates a list of images available

function buildImageList ( $baseURL, $baseDir, $albumDir, $currDir,
                          $albumURLroot, $maxColumns, $maxRows, $markerType,
                          $markerLabel, $directoryList, $suppressImageInfo,
                          $useThumbSubdir, $thumbSubdir, $noThumbs, $thumbExt,
                          $suppressAltTags, $sortType, $hidden, $presorted,
                          $description, $short_desc, $imagePopup,
                          $imagePopType, $imagePopLocationBar,
                          $imagePopMenuBar, $imagePopToolBar,
                          $commentFilePerImage, $startFrom,
                          $commentFileShortComments, $showShortOnThumbPage )
{
    global $mig_config;

    if (is_dir("$albumDir/$currDir")) {
        $dir = opendir("$albumDir/$currDir"); // Open directory handle
    } else {
        print "ERROR: no such currDir '$currDir'<br>";
        exit;
    }

    // URL-encoded version of currDir
    $urlCurrDir = migURLencode($currDir);

    $row = 0;               // Counters for the table formatting
    $col = 0;

    --$maxColumns;          // Tricks maxColumns into working since it
                            // really starts at 0, not 1.

    --$maxRows;             // same for rows

    // prototype the arrays
    $imagefiles     = array ();
    $filedates      = array ();

    $thumbsInFolder = 0;

    // Count presorted images for pagination purposes
    if ($presorted) {
        while (list($x,$y) = each($presorted)) {
            ++$thumbsInFolder;
        }
    }

    // Reset array pointer
    reset($presorted);

    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$useThumbSubdir) {  // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == 'suffix' && ereg("_$markerLabel\.[^.]+$", $file)
                && validFileType($file)) {
                    continue;
            }

            if ($markerType == 'prefix' && ereg("^$markerLabel\_", $file)) {
                continue;
            }

        }

        // We'll look at this one only if it's a file
        // and it matches our list of approved extensions
        if (is_file("$albumDir/$currDir/$file")
                        && ! $presorted[$file] && validFileType($file))
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

    // Make sure hidden items don't show up
    while (list($file,$junk) = each($hidden))
        unset ($presorted[$file]);

    reset($presorted);          // reset array pointer

    // If there are images, start the table
    if ($thumbsInFolder) {
        $imageList .= "\n  " . '<table summary="Image Links" border="0"'
                    . ' cellspacing="0"><tbody>';
    }

    // Set up pagination environment
    $max_col = $maxColumns + 1;
    $max_row = $maxRows + 1;
    $firstThumb = $startFrom * $max_col * $max_row;
    // This rounds off any fractional part
    $pages = ceil($thumbsInFolder / ($max_col * $max_row));

    // Handle pagination
    if ($thumbsInFolder > ($max_col * $max_row)) {

        if ($startFrom) {
            $start_img = ($startFrom * $max_col * $max_row) + 1;

            if (($start_img+($max_col*$max_row)-1) >= $thumbsInFolder) {
                // This must be the last page.
                $end_img = $thumbsInFolder;
            } else {
                // Not the first, not last - some middle page.
                $end_img = ($startFrom+1) * $max_col * $max_row;
            }

        } else {
            // Absence of $startFrom means we're on page 1 (startFrom=0).
            // Therefore, we can easily calculate what we need.
            $start_img = 1;
            $end_img = $max_col * $max_row;
        }

        // Fetch template phrase to work with.
        $phrase = $mig_config['lang']['total_images'];
        // %t is total images in folder
        $phrase = str_replace('%t', $thumbsInFolder, $phrase);
        // %s is start image
        $phrase = str_replace('%s', $start_img, $phrase);
        // %e is end image
        $phrase = str_replace('%e', $end_img, $phrase);

        $imageList .= "\n   " . '<tr>' . "\n    " . '<td colspan="'
                    . $max_col . '" align="center"><small>' . $phrase;

        if ($startFrom) {
            $prevPage = $startFrom - 1;

            $imageList .= '<a href="' . $baseURL
                        . '?pageType=folder&currDir=' . $urlCurrDir
                        . '&startFrom=' . $prevPage
                        . '">&laquo;</A>&nbsp;&nbsp;';
        }

        for ($i = 1; $i <= $pages; ++$i) {
            if (floor(($i - 11) / 20) == (($i - 11) / 20)) {
                $imageList .= '<br />';
            }
            if ($i == ($startFrom + 1)) {
                $imageList .= '<b>' . $i . '</b>&nbsp;&nbsp;';
            } else {
                $ib = $i - 1;
                $imageList .= '<a href="' . $baseURL
                            . '?pageType=folder&currDir=' . $urlCurrDir
                            . '&startFrom=' . $ib . '">'
                            . $i . '</a>&nbsp;&nbsp;';
            }
        }

        if (($startFrom + 1) < $pages) {
            $nextPage = $startFrom + 1;
            $imageList .= '<a href="' . $baseURL
                        . '?pageType=folder&currDir=' . $urlCurrDir
                        . '&startFrom=' . $nextPage . '">&raquo;</A>';
        }

        $imageList .= "</small></td>\n   </tr>";
    }

    $thumbCounter = -1;

    while (list($file,$junk) = each($presorted)) {

        ++$thumbCounter;

        if ($thumbCounter >= $firstThumb && $row <= $maxRows) {

            // Only look at valid image types
            if (validFileType($file)) {

                // If this is a new row, start a new <TR>
                if ($col == 0) {
                    $imageList .= "\n   <tr>";
                }

                $img = buildImageURL($baseURL, $baseDir, $albumDir, $currDir,
                                     $albumURLroot, $file, $suppressImageInfo,
                                     $markerType, $markerLabel,
                                     $useThumbSubdir, $thumbSubdir, $noThumbs,
                                     $thumbExt, $suppressAltTags, $description,
                                     $short_desc, $imagePopup, $imagePopType,
                                     $imagePopLocationBar, $imagePopMenuBar,
                                     $imagePopToolBar, $commentFilePerImage,
                                     $startFrom, $commentFileShortComments,
                                     $showShortOnThumbPage);
                $imageList .= $img;

                // Keep track of what row and column we are on
                if ($col == $maxColumns) {
                    $imageList .= "\n   </tr>";
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
        // Stick a </tr> on the end if it isn't there already and close
        // the table
        $imageList .= "\n  </tr>\n  </tbody></table>";
    } else {
        // Close the table.
        $imageList .= "\n  </tbody></table>";
    }

    return $imageList;

}   // -- End of buildImageList()

