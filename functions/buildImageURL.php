
// buildImageURL() - spit out HTML for a particular image

function buildImageURL ( $baseURL, $baseDir, $albumDir, $currDir,
                         $albumURLroot, $filename, $suppressImageInfo,
                         $markerType, $markerLabel, $useThumbSubdir,
                         $thumbSubdir, $noThumbs, $thumbExt, $suppressAltTags,
                         $description, $short_desc, $imagePopup,
                         $imagePopType, $imagePopLocationBar,
                         $imagePopMenuBar, $imagePopToolBar,
                         $commentFilePerImage, $startFrom,
                         $commentFileShortComments, $showShortOnThumbPage,
                         $imagePopMaxWidth, $imagePopMaxHeight, $pageType )
{
    global $mig_config;
    global $mig_dl;

    $fname = getFileName($filename);
    $ext = getFileExtension($filename);

    // newCurrDir is currDir without leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // URL-encode currDir, keeping an old copy too
    $oldCurrDir = $currDir;
    $currDir = migURLencode($currDir);

    // URL-encoded the filename
    $newFname = rawurlencode($fname);

    // Only show a thumbnail if one exists.  Otherwise use a default
    // "generic" thumbnail image.

    if ($useThumbSubdir) {

        if ($thumbExt) {
            $thumbFile = "$albumDir/$oldCurrDir/$thumbSubdir/$fname.$thumbExt";
        } else {
            $thumbFile = "$albumDir/$oldCurrDir/$thumbSubdir/$fname.$ext";
        }

    } else {

        if ($markerType == 'prefix') {
            $thumbFile  = "$albumDir/$oldCurrDir/$markerLabel";

            if ($thumbExt) {
                $thumbFile .= "_$fname.$thumbExt";
            } else {
                $thumbFile .= "_$fname.$ext";
            }
        }

        if ($markerType == 'suffix') {
            $thumbFile  = "$albumDir/$oldCurrDir/$fname";

            if ($thumbExt) {
                $thumbFile .= "_$markerLabel.$thumbExt";
            } else {
                $thumbFile .= "_$markerLabel.$ext";
            }
        }
    }

    if (file_exists($thumbFile)) {
        if ($useThumbSubdir) {
            $thumbImage  = "$albumURLroot/$currDir/$thumbSubdir";

            if ($thumbExt) {
                $thumbImage .= "/$fname.$thumbExt";
            } else {
                $thumbImage .= "/$fname.$ext";
            }

        } else {

            if ($markerType == 'prefix') {
                $thumbImage  = "$albumURLroot/$currDir/$markerLabel";

                if ($thumbExt) {
                    $thumbImage .= "_$fname.$thumbExt";
                } else {
                    $thumbImage .= "_$fname.$ext";
                }
            }

            if ($markerType == 'suffix') {
                $thumbImage  = "$albumURLroot/$currDir/$fname";

                if ($thumbExt) {
                    $thumbImage .= "_$markerLabel.$thumbExt";
                } else {
                    $thumbImage .= "_$markerLabel.$ext";
                }
            }
        }
        $thumbImage = migURLencode($thumbImage);

    } else {
        $newRoot = ereg_replace('/[^/]+$', '', $baseURL);
        $thumbImage = $newRoot . '/images/no_thumb.gif';
    }

    // Get description, if any
    if ($commentFilePerImage) {
        list($alt_desc, $x) = getImageDescFromFile("$fname.$ext", $albumDir,
                                        $currDir, $commentFileShortComments);
        // Get a conventional comment if there isn't one here.
        if (! $alt_desc) {
            list($alt_desc, $desc) = getImageDescription("$fname.$ext",
                                                $description, $short_desc);
        }
    } else {
        list($alt_desc, $desc) = getImageDescription("$fname.$ext",
                                                $description, $short_desc);
    }

    // If there's a full description but no alt, use the full as alt.
    if ($desc && ! $alt_desc) {
        $alt_desc = $desc;
    }

    $alt_desc = strip_tags($alt_desc);

    // Figure out the image's size (in bytes and pixels) for display
    $imageFile = "$albumDir/$oldCurrDir/$fname.$ext";

    // Figure out the pixels
    $imageProps = GetImageSize($imageFile);
    $imageWidth = $imageProps[0];
    $imageHeight = $imageProps[1];

    // Figure out the bytes
    $imageSize = filesize($imageFile);

    if ($imageSize > 1048576) {
        $imageSize = sprintf('%01.1f', $imageSize / 1024 / 1024) . 'MB';
    } elseif ($imageSize > 1024) {
        $imageSize = sprintf('%01.1f', $imageSize / 1024) . 'KB';
    } else {
        $imageSize = $imageSize . $mig_config['lang']['bytes'];
    }

    // Figure out thumbnail geometry
    $thumbHTML = '';
    if (file_exists($thumbFile)) {
        $thumbProps = GetImageSize($thumbFile);
        $thumbHTML = $thumbProps[3];
    }

    // beginning of the table cell
    $url = "\n    " . '<td align="center" class="image"><a';

    if (!$suppressAltTags) {
        $url .= ' title="' . $alt_desc . '"';
    }

    $url .= ' href="';

    // set up the image pop-up if appropriate to do so
    if ($imagePopup) {
        $popup_width = $imageWidth + 30;
        $popup_height = $imageHeight + 150;
        
        // Add max size for popup window
        if ($popup_width > $imagePopMaxWidth) {
            $popup_width = $imagePopMaxWidth;
        }
        if ($popup_height > $imagePopMaxHeight) {
            $popup_height = $imagePopMaxHeight;
        }
        $url .= '#" onClick="window.open(\'';
    }

    $url .= $baseURL . '?currDir='
         . $currDir . '&amp;pageType=image&amp;image=' . $newFname
         . '.' . $ext;

    if ($startFrom) {
        $url .= '&amp;startFrom=' . $startFrom;
    }

    if ($mig_dl) {
        $url .= '&amp;mig_dl=' . $mig_dl;
    }

    if ($imagePopup) {
        $url .= "','";

        if ($imagePopType == 'reuse') {
            $url .= 'mig_window_11190874';
        } else {
            $url .= 'mig_window_' . time() . '_' . $newFname;
        }

        $url .= "','width=$popup_width,height=$popup_height,"
              . "resizable=yes,scrollbars=1";

        // Set up various toolbar options if requested

        if ($imagePopLocationBar) {
            $url .= ",location=1";
        }
        if ($imagePopToolBar) {
            $url .= ",toolbar=1";
        }
        if ($imagePopMenuBar) {
            $url .= ",menubar=1";
        }

        $url .= "');";
    }

    $url .= '">';

    // If $noThumbs is true, just print the image filename rather
    // than the <IMG> tag pointing to a thumbnail.
    if ($noThumbs) {
        $url .= "$newFname.$ext";
    } else {
        $url .= '<img src="' . $thumbImage . '"';
            // Only print the ALT tag if it's wanted.
            if (! $suppressAltTags) {
                $url .= ' alt="' . $alt_desc . '"';
            }

        $url .= ' border="0" ' . $thumbHTML . '/>';
    }

    $url .= '</a>';     // End the <A> element

    // If $suppressImageInfo is FALSE, show the image info
    if (!$suppressImageInfo) {
        $url .= '<br />';
        if (!$noThumbs) {
            $url .= $fname . '.' . $ext . '<br />';
        }

        $url .= '(' . $imageWidth . 'x' . $imageHeight . ', '
             . $imageSize . ')';
    }

    // If $showShortOnThumbPage is TRUE, show short comment
    if ($showShortOnThumbPage) {
        $url .= '<br />';
        $url .= $alt_desc;
    }

    $url .= '</td>';        // Close table cell
    return $url;

}   // -- End of buildImageURL()

