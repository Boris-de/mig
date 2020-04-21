<?php

// buildImageList() - Creates a list of images for display.

function buildImageList ( $currDir, $maxColumns, $maxRows,
                          $presorted, $description, $short_desc )
{
    global $mig_config;

    if (is_dir($mig_config['albumdir'].'/'.$currDir)) {
        $dir = opendir($mig_config['albumdir'].'/'.$currDir);
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
    $imageList      = '';
    $pageBlock      = '';

    // Count presorted images for pagination purposes
    if ($presorted) {
        foreach ($presorted as $x => $y) {
            ++$thumbsInFolder;
        }
    }

    $markerLabel = $mig_config['markerlabel'];
    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$mig_config['usethumbsubdir']) {
                         // unless $useThumbSubdir is set,
                         // then don't waste time on this check

            if ($mig_config['markertype'] == 'suffix' && preg_match("#_$markerLabel\.[^.]+$#", $file)
                && getFileType($file)) {
                    continue;
            }

            if ($mig_config['markertype'] == 'prefix' && preg_match("#^$markerLabel\_#", $file)) {
                continue;
            }

        }

        // We'll look at this one only if it's a file
        // and it matches our list of approved extensions
        if (is_file($mig_config['albumdir'].'/'.$currDir.'/'.$file)
                        && ! isset($presorted[$file]) && getFileType($file)
                        && preg_match($mig_config['imageFilenameRegexpr'], $file))
        {
            // Increase thumb counter
            ++$thumbsInFolder;

            // Stash file in an array
            $imagefiles[$file] = TRUE;

            // and stash a timestamp as well if needed
            if (preg_match('#bydate.*#', $mig_config['sorttype'])) {
                $timestamp = filemtime($mig_config['albumdir']
                                       . "/$currDir/$file");
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    ksort($imagefiles); // sort, so we get a sorted list to stuff onto the
                        // end of $presorted

    if ($mig_config['sorttype'] == 'bydate-ascend') {
        ksort($filedates);

    } elseif ($mig_config['sorttype'] == 'bydate-descend') {
        krsort($filedates);
    }

    // Join the two sorted lists together into a single list
    if (preg_match('#bydate.*#', $mig_config['sorttype'])) {
        foreach (array_values($filedates) as $file) {
            $presorted[$file] = TRUE;
        }

    } else {
        foreach (array_keys($imagefiles) as $file) {
            $presorted[$file] = TRUE;
        }
    }

    // Make sure hidden items don't show up
    foreach ($mig_config['hidden'] as $file => $junk) {
        unset ($presorted[$file]);
    }

    // If there are images, start the table
    if ($thumbsInFolder) {
        $imageList .= "\n  " . '<table summary="Image Links" border="0"'
                    . ' cellspacing="0"><tbody>';
    }

    // Set up pagination environment
    $max_col = $maxColumns + 1;
    $max_row = $maxRows + 1;
    $firstThumb = $mig_config['startfrom'] * $max_col * $max_row;
    // This rounds off any fractional part
    $pages = ceil($thumbsInFolder / ($max_col * $max_row));

    // show last page if $startfrom to big
    if ($thumbsInFolder<$firstThumb) {
        $mig_config['startfrom']=$pages-1;
        $firstThumb = $mig_config['startfrom'] * $max_col * $max_row;
    }

    // Handle pagination
    if ($thumbsInFolder > ($max_col * $max_row)) {

        if ($mig_config['startfrom']) {
            $start_img = ($mig_config['startfrom'] * $max_col * $max_row) + 1;

            if (($start_img+($max_col*$max_row)-1) >= $thumbsInFolder) {
                // This must be the last page.
                $end_img = $thumbsInFolder;
            } else {
                // Not the first, not last - some middle page.
                $end_img = ($mig_config['startfrom'] + 1) * $max_col * $max_row;
            }

        } else {
            // Absence of startFrom means we're on page 1 (startFrom=0).
            // Therefore, we can easily calculate what we need.
            $start_img = 1;
            $end_img = $max_col * $max_row;
        }

        // Fetch template phrase to work with.
        if ($mig_config['showTotalImagesString']) {
            $phrase = $mig_config['lang']['total_images'];
            // %t is total images in folder
            $phrase = str_replace('%t', $thumbsInFolder, $phrase);
            // %s is start image
            $phrase = str_replace('%s', $start_img, $phrase);
            // %e is end image
            $phrase = str_replace('%e', $end_img, $phrase);
        } else {
            $phrase = '';
        }

        $pageBlock .= "\n" . '   <tr>' . "\n" . '    <td colspan="'
                    . $max_col . '" align="center"><small>' . $phrase;

        if ($mig_config['startfrom']) {
            $prevPage = $mig_config['startfrom'] - 1;

            $pageBlock .= '<a href="' . $mig_config['baseurl']
                        . '?pageType=folder&amp;currDir=' . $urlCurrDir
                        . '&amp;startFrom=' . $prevPage;
            if ($mig_config['mig_dl']) {
                $pageBlock .= '&amp;mig_dl=' . $mig_config['mig_dl'];
            }
            $pageBlock .= '">&laquo;</a>&nbsp;&nbsp;';
        }

        for ($i = 1; $i <= $pages; ++$i) {
            if (floor(($i - 11) / 20) == (($i - 11) / 20)) {
                $pageBlock .= '<br />';
            }
            if ($i == ($mig_config['startfrom'] + 1)) {
                $pageBlock .= '<b>' . $i . '</b>&nbsp;&nbsp;';
            } else {
                $ib = $i - 1;
                $pageBlock .= '<a href="' . $mig_config['baseurl']
                            . '?pageType=folder&amp;currDir=' . $urlCurrDir
                            . '&amp;startFrom=' . $ib;
                if ($mig_config['mig_dl']) {
                    $pageBlock .= '&amp;mig_dl=' . $mig_config['mig_dl'];
                }
                $pageBlock .= '">' . $i . '</a>&nbsp;&nbsp;';
            }
        }

        if (($mig_config['startfrom'] + 1) < $pages) {
            $nextPage = $mig_config['startfrom'] + 1;
            $pageBlock .= '<a href="' . $mig_config['baseurl']
                        . '?pageType=folder&amp;currDir=' . $urlCurrDir
                        . '&amp;startFrom=' . $nextPage;
            if ($mig_config['mig_dl']) {
                $pageBlock .= '&amp;mig_dl=' . $mig_config['mig_dl'];
            }
            $pageBlock .= '">&raquo;</a>';
        }

        $pageBlock .= "</small></td>\n   </tr>";
    }

    $imageList .= $pageBlock;

    $thumbCounter = -1;

    foreach ($presorted as $file => $junk) {

        ++$thumbCounter;

        if ($thumbCounter >= $firstThumb && $row <= $maxRows) {

            // Only look at valid image types
            if (getFileType($file)) {

                // If this is a new row, start a new <TR>
                if ($col == 0) {
                    $imageList .= "\n   <tr>";
                }

                $img = buildImageURL($currDir, $file, $description, $short_desc);
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

    $imageList .= $pageBlock;

    // If there aren't any images to work with, just say so.
    if ($imageList == '') {
        $imageList = '';
    } elseif (!preg_match('#</tr>$#i', $imageList)) {
        // Stick a </tr> on the end if it isn't there already and close
        // the table
        $imageList .= "\n  </tr>\n  </tbody></table>";
    } else {
        // Close the table.
        $imageList .= "\n  </tbody></table>";
    }

    return $imageList;

}   // -- End of buildImageList()

?>
