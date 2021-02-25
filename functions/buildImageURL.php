<?php

function _addThumbExt($name, $unsafe_ext) {
    global $mig_config;
    if ($name) {
        if ($mig_config['thumbext']) {
            $name .= $mig_config['thumbext'];
        } else {
            $name .= $unsafe_ext;
        }
    }
    return $name;
}

// buildImageURL() - Create HTML link for a particular image.

function buildImageURL ($unsafe_currDir, $unsafe_filename, $description, $short_desc )
{
    global $mig_config;

    // Collect information about this object.
    $unsafe_fname  = getFileName($unsafe_filename);
    $unsafe_ext    = getFileExtension($unsafe_filename);
    $type       = getFileType($unsafe_filename);

    // URL-encoded the filename
    $enc_fname = rawurlencode($unsafe_fname);
    $enc_ext   = rawurlencode($unsafe_ext);

    // URL-encode currDir, keeping an old copy too
    $enc_oldCurrDir = rawurlencode($unsafe_currDir);
    $enc_currDir = migURLencode($unsafe_currDir);

    // local Filename of the thumb
    $local_thumbFile = '';
    if ($type == 'image') {
        $local_dir = $mig_config['albumdir'] . "/$unsafe_currDir/";
        if ($mig_config['usethumbsubdir']) {
            $local_thumbFile = $local_dir . $mig_config['thumbsubdir'] . "/$unsafe_fname.";
        } elseif ($mig_config['markertype'] == 'prefix') {
            $local_thumbFile = $local_dir . $mig_config['markerlabel'] . "_$unsafe_fname.";
        } elseif ($mig_config['markertype'] == 'suffix') {
            $local_thumbFile = $local_dir . $unsafe_fname . "_{$mig_config['markerlabel']}.";
        }

        // if a thumbnail could be there
        $local_thumbFile = _addThumbExt($local_thumbFile, $unsafe_ext);
    }

    // Only show a thumbnail if one exists.  Otherwise use a default
    // "generic" thumbnail image.

    $enc_thumbImage = '';
    if (file_exists($local_thumbFile) && $type == 'image') {
        $unsafe_thumbImage = '';
        $unsafe_path = $mig_config['albumurlroot'] . "/$unsafe_currDir/";
        if ($mig_config['usethumbsubdir']) {
            $unsafe_thumbImage = $unsafe_path . $mig_config['thumbsubdir'] . "/$unsafe_fname.";
        } elseif ($mig_config['markertype'] == 'prefix') {
            $unsafe_thumbImage = $unsafe_path . $mig_config['markerlabel'] . "_$unsafe_fname.";
        } elseif ($mig_config['markertype'] == 'suffix') {
            $unsafe_thumbImage = $unsafe_path . "${unsafe_fname}_{$mig_config['markerlabel']}.";
        }

        // if a thumbnail could be there
        $unsafe_thumbImage = _addThumbExt($unsafe_thumbImage, $unsafe_ext);

        $enc_thumbImage = migURLencode($unsafe_thumbImage);

    } else {
        $newRoot = preg_replace('#/[^/]+$#', '', $mig_config['baseurl']);
        switch ($type) {
            case 'image':
                $enc_thumbImage = $newRoot . '/images/' . $mig_config['nothumb_icon'];
                break;
            case 'audio':
                $enc_thumbImage = $newRoot . '/images/' . $mig_config['music_icon'];
                break;
            case 'video':
                $enc_thumbImage = $newRoot . '/images/' . $mig_config['movie_icon'];
                break;
        }
    }

    // Get description, if any
    if ($mig_config['commentfileperimage']) {
        list($alt_desc, $desc) = getImageDescFromFile($enc_currDir, "$unsafe_fname.$unsafe_ext");
        // Get a conventional comment if there isn't one here.
        if (! $alt_desc) {
            list($alt_desc, $desc) = getImageDescription("$unsafe_fname.$unsafe_ext", $description, $short_desc);
        }
    } else {
        list($alt_desc, $desc) = getImageDescription("$unsafe_fname.$unsafe_ext", $description, $short_desc);
    }

    // If there's a full description but no alt, use the full as alt.
    if ($desc && ! $alt_desc) {
        $alt_desc = $desc;
    }

    $alt_desc = strip_tags($alt_desc);

    $localFilename = $mig_config['albumdir'] . "/$unsafe_currDir/$unsafe_filename";

    // Figure out the size in bytes for display
    $imageFileSize = filesize($localFilename);

    $imageWidth = 0;
    $imageHeight = 0;
    if ($type == 'image') {
        // Figure out the size in pixels for display
        $imageProps = @GetImageSize($localFilename);
        if ($imageProps) {
            $imageWidth = $imageProps[0];
            $imageHeight = $imageProps[1];
        }
    }

    if ($imageFileSize > 1048576) {
       $imageFileSize = sprintf('%01.1f', $imageFileSize / 1024 / 1024) . 'MB';
    } elseif ($imageFileSize > 1024) {
        $imageFileSize = sprintf('%01.1f', $imageFileSize / 1024) . 'KB';
    } else {
       $imageFileSize = $imageFileSize . $mig_config['lang']['bytes'];
    }


    // Figure out thumbnail geometry
    $thumbHTML = '';
    if (file_exists($local_thumbFile) && $type == 'image') {
        $thumbProps = @GetImageSize($local_thumbFile);
        if ($thumbProps) {
            $thumbHTML = $thumbProps[3];
        }
    }

    // If not an image, just print a URL to the object
    // with a few extra trimmings.
    if ($type != 'image') {

        $url = "\n" . '    <td align="center" class="image"><a';
        if (! $mig_config['suppressalttags']) {
            $url .= ' title="' . $alt_desc . '"';
        }

        $url .= ' href="' . $mig_config['albumurlroot'] . '/' . $enc_currDir . '/'
              . $enc_fname . '.' . $enc_ext . '">'
              . '<img src="' . $enc_thumbImage . '" /></a>';


        // If $fileInfoFormatString is set, show the file info
        if ($mig_config['fileinfoformatstring']) {
            $url .= '<br />';

            $fileinfotable = array(
                'n' => migHtmlSpecialChars($unsafe_filename),
                's' => $imageFileSize
                // 'i' => $imageWidth.'x'.$imageHeight
            );
            $newstr=replaceString($mig_config['fileinfoformatstring'][$type],$fileinfotable);

            if (!$mig_config['nothumbs']) {
                $url .= $newstr;
            }
        }

        $url .= '</td>';

        return $url;

    // It's an image - jump through all the hoops
    } else {

        // beginning of the table cell
        $url = "\n" . '    <td align="center" class="image"><a';

        if (! $mig_config['suppressalttags']) {
            $url .= ' title="' . $alt_desc . '"';
        }

        $url .= ' href="';

        // set up the image pop-up if appropriate to do so
        if ($mig_config['imagepopup']) {
            $popup_width = $imageWidth + 30;
            $popup_height = $imageHeight + 150;

            // Add max size for popup window
            if ($popup_width > $mig_config['imagepopmaxwidth']) {
                $popup_width = $mig_config['imagepopmaxwidth'];
            }
            if ($popup_height > $mig_config['imagepopmaxheight']) {
                $popup_height = $mig_config['imagepopmaxheight'];
            }
            $url .= '#" onClick="window.open(\'';
        }

        $url .= $mig_config['baseurl'] . '?currDir='
             . $enc_currDir . '&amp;pageType=image&amp;image=' . $enc_fname . '.' . $enc_ext;

        if ($mig_config['startfrom']) {
            $url .= '&amp;startFrom=' . $mig_config['startfrom'];
        }

        if ($mig_config['mig_dl']) {
            $url .= '&amp;mig_dl=' . $mig_config['mig_dl'];
        }

        if ($mig_config['imagepopup']) {
            assert(isset($popup_width) && isset($popup_height));
            $url .= "','";

            if ($mig_config['imagepoptype'] == 'reuse') {
                $url .= 'mig_window_11190874';
            } else {
                $url .= 'mig_window_' . time() . '_' . $enc_fname;
            }

            $url .= "','width=$popup_width,height=$popup_height,"
                  . 'resizable=yes,scrollbars=1';

            // Set up various toolbar options if requested

            if ($mig_config['imagepoplocationbar']) {
                $url .= ',location=1';
            }
            if ($mig_config['imagepoptoolbar']) {
                $url .= ',toolbar=1';
            }
            if ($mig_config['imagepopmenubar']) {
                $url .= ',menubar=1';
            }

            $url .= "');return false;";
        }

        $url .= '">';

        // If $noThumbs is true, just print the image filename rather
        // than the <IMG> tag pointing to a thumbnail.
        if ($mig_config['nothumbs']) {
            $url .= "$enc_fname.$enc_ext";
        } else {
            $url .= '<img src="' . $enc_thumbImage . '"';
                // Only print the ALT tag if it's wanted.
                if (! $mig_config['suppressalttags']) {
                    $url .= ' alt="' . $alt_desc . '"';
                }

            $url .= ' class="imagethumb" ' . $thumbHTML . ' />';
        }

        $url .= '</a>';     // End the <A> element

        // If $fileInfoFormatString is set, show the image info
        if ($mig_config['fileinfoformatstring']) {
            $url .= '<br />';
            //replace variables of the fileinfoformatstring
            //       %n = Filename
            //       %s = FileSize
            //       %i = ImageSize
            $fileinfotable = array(
                'n' => migHtmlSpecialChars($unsafe_fname . '.' . $unsafe_ext),
                's' => $imageFileSize,
                'i' => $imageWidth > 0 ? $imageWidth . 'x' . $imageHeight : ''
            );

             $newstr=replaceString($mig_config['fileinfoformatstring'][$type],$fileinfotable);


             if (!$mig_config['nothumbs']) {
                $url .= $newstr;
            }
        }

        // If $showShortOnThumbPage is TRUE, show short comment
        if ($mig_config['showshortonthumbpage']) {
            $url .= '<br />';
            $url .= $alt_desc;
        }

        $url .= '</td>';        // Close table cell
        return $url;
    }

}   // -- End of buildImageURL()

?>
