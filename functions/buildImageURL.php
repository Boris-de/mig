

// buildImageURL() - Create HTML link for a particular image.

function buildImageURL ( $currDir, $filename, $description, $short_desc )
{
    global $mig_config;
    
    $markerLabel = $mig_config["markerlabel"];


    // Collect information about this object.
    $fname  = getFileName($filename);
    $ext    = getFileExtension($filename);
    $type   = getFileType($filename);

    // newCurrDir is currDir without leading "./"
    $newCurrDir = getNewCurrDir($currDir);

    // URL-encode currDir, keeping an old copy too
    $oldCurrDir = $currDir;
    $currDir = migURLencode($currDir);

    // URL-encoded the filename
    $newFname = rawurlencode($fname);

    // Only show a thumbnail if one exists.  Otherwise use a default
    // "generic" thumbnail image.

    if ($type == "image") {
        if ($mig_config["usethumbsubdir"]) {

            if ($mig_config["thumbext"]) {
                $thumbFile = $mig_config["albumdir"]."/$oldCurrDir/"
                           . $mig_config["thumbsubdir"]
                           . "/$fname." . $mig_config["thumbext"];
            } else {
                $thumbFile = $mig_config["albumdir"]."/$oldCurrDir/"
                           . $mig_config["thumbsubdir"]
                           . "/$fname.$ext";
            }

        } else {

            if ($mig_config["markertype"] == "prefix") {
                $thumbFile  = $mig_config["albumdir"]."/$oldCurrDir/"
                            . $mig_config["markerlabel"];

                if ($mig_config["thumbext"]) {
                    $thumbFile .= "_$fname." . $mig_config["thumbext"];
                } else {
                    $thumbFile .= "_$fname.$ext";
                }
            }

            if ($mig_config["markertype"] == "suffix") {
                $thumbFile  = $mig_config["albumdir"]."/$oldCurrDir/$fname";

                if ($mig_config["thumbext"]) {
                    $thumbFile .= "_$markerLabel." . $mig_config["thumbext"];
                } else {
                    $thumbFile .= "_$markerLabel.$ext";
                }
            }
        }
    }

    if (file_exists($thumbFile)) {
        if ($mig_config["usethumbsubdir"]) {
            $thumbImage  = $mig_config["albumurlroot"] . "/$currDir/"
                         . $mig_config["thumbSubdir"];

            if ($mig_config["thumbext"]) {
                $thumbImage .= "/$fname." . $mig_config["thumbext"];
            } else {
                $thumbImage .= "/$fname.$ext";
            }

        } else {

            if ($mig_config["markertype"] == "prefix") {
                $thumbImage  = $mig_config["albumurlroot"]
                             . "/$currDir/".$mig_config["markerlabel"];

                if ($mig_config["thumbext"]) {
                    $thumbImage .= "_$fname." . $mig_config["thumbext"];
                } else {
                    $thumbImage .= "_$fname.$ext";
                }
            }

            if ($mig_config["markertype"] == "suffix") {
                $thumbImage  = $mig_config["albumurlroot"]
                             . "/$currDir/$fname";

                if ($mig_config["thumbext"]) {
                    $thumbImage .= "_$markerLabel." . $mig_config["thumbext"];
                } else {
                    $thumbImage .= "_$markerLabel.$ext";
                }
            }
        }
        $thumbImage = migURLencode($thumbImage);

    } else {
        $newRoot = ereg_replace("/[^/]+$", "", $mig_config["baseurl"]);
        switch ($type) {
            case "image":
                $thumbImage = $newRoot . "/images/no_thumb.gif";
                break;
            case "audio":
                $thumbImage = $newRoot . "/images/music.gif";
                break;
            case "video":
                $thumbImage = $newRoot . "/images/movie.gif";
                break;
        }
    }

    // Get description, if any
    if ($mig_config["commentfileperimage"]) {
        list($alt_desc, $x) = getImageDescFromFile("$fname.$ext", $currDir);
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

    if ($type == "image") {
        // Figure out the image's size (in bytes and pixels) for display
        $imageFile = $mig_config["albumdir"]."/$oldCurrDir/$fname.$ext";

        // Figure out the pixels
        $imageProps = GetImageSize($imageFile);
        $imageWidth = $imageProps[0];
        $imageHeight = $imageProps[1];

        // Figure out the bytes
        $imageSize = filesize($imageFile);

        if ($imageSize > 1048576) {
            $imageSize = sprintf("%01.1f", $imageSize / 1024 / 1024) . "MB";
        } elseif ($imageSize > 1024) {
            $imageSize = sprintf("%01.1f", $imageSize / 1024) . "KB";
        } else {
            $imageSize = $imageSize . $mig_config["lang"]["bytes"];
        }
    }

    // Figure out thumbnail geometry
    $thumbHTML = "";
    if (file_exists($thumbFile)) {
        $thumbProps = GetImageSize($thumbFile);
        $thumbHTML = $thumbProps[3];
    }

    // If not an image, just print a URL to the object
    // with a few extra trimmings.
    if ($type != "image") {

        $url = "\n    <td align=\"center\" class=\"image\"><a";
        if (! $mig_config["suppressalttags"]) { 
            $url .= " title=\"" . $alt_desc . "\"";
        }

        $url .= " href=\"" . $mig_config["albumurlroot"] . "/" . $currDir . "/"
              . $fname . "." . $ext . "\">"
              . "<img src=\"" . $thumbImage . "\"></a>";

        // If $suppressImageInfo is FALSE, show the file info
        if (!$mig_config["suppressimageinfo"]) {
            $url .= "<br />";
            if (!$mig_config["nothumbs"]) {
                $url .= $fname . "." . $ext . "<br />"
                      . "(" . $type . ")<br />";
            }
        }

        $url .= "</td>";

        return $url;

    // It's an image - jump through all the hoops
    } else {

        // beginning of the table cell
        $url = "\n    <td align=\"center\" class=\"image\"><a";

        if (! $mig_config["suppressalttags"]) {
            $url .= " title=\"" . $alt_desc . "\"";
        }

        $url .= " href=\"";

        // set up the image pop-up if appropriate to do so
        if ($mig_config["imagepopup"]) {
            $popup_width = $imageWidth + 30;
            $popup_height = $imageHeight + 150;
        
            // Add max size for popup window
            if ($popup_width > $mig_config["imagepopmaxwidth"]) {
                $popup_width = $mig_config["imagepopmaxwidth"];
            }
            if ($popup_height > $mig_config["imagepopmaxheight"]) {
                $popup_height = $mig_config["imagepopmaxheight"];
            }
            $url .= "#\" onClick=\"window.open('";
        }

        $url .= $mig_config["baseurl"] . "?currDir="
             . $currDir . "&amp;pageType=image&amp;image=" . $newFname
             . "." . $ext;

        if ($mig_config["startfrom"]) {
            $url .= "&amp;startFrom=" . $mig_config["startfrom"];
        }

        if ($mig_config["mig_dl"]) {
            $url .= "&amp;mig_dl=" . $mig_config["mig_dl"];
        }

        if ($mig_config["imagepopup"]) {
            $url .= "','";

            if ($mig_config["imagepoptype"] == "reuse") {
                $url .= "mig_window_11190874";
            } else {
                $url .= "mig_window_" . time() . "_" . $newFname;
            }

            $url .= "','width=$popup_width,height=$popup_height,"
                  . "resizable=yes,scrollbars=1";

            // Set up various toolbar options if requested

            if ($mig_config["imagepoplocationbar"]) {
                $url .= ",location=1";
            }
            if ($mig_config["imagepoptoolbar"]) {
                $url .= ",toolbar=1";
            }
            if ($mig_config["imagepopmenubar"]) {
                $url .= ",menubar=1";
            }

            $url .= "');";
        }

        $url .= "\">";

        // If $noThumbs is true, just print the image filename rather
        // than the <IMG> tag pointing to a thumbnail.
        if ($mig_config["nothumbs"]) {
            $url .= "$newFname.$ext";
        } else {
            $url .= "<img src=\"" . $thumbImage . "\"";
                // Only print the ALT tag if it's wanted.
                if (! $mig_config["suppressalttags"]) {
                    $url .= " alt=\"" . $alt_desc . "\"";
                }

            $url .= " border=\"0\" " . $thumbHTML . "/>";
        }

        $url .= "</a>";     // End the <A> element

        // If $suppressImageInfo is FALSE, show the image info
        if (!$mig_config["suppressimageinfo"]) {
            $url .= "<br />";
            if (!$mig_config["nothumbs"]) {
                $url .= $fname . "." . $ext . "<br />";
            }

            $url .= "(" . $imageWidth . "x" . $imageHeight . ", "
                  . $imageSize . ")";
        }

        // If $showShortOnThumbPage is TRUE, show short comment
        if ($mig_config["showshortonthumbpage"]) {
            $url .= "<br />";
            $url .= $alt_desc;
        }

        $url .= "</td>";        // Close table cell
        return $url;
    }

}   // -- End of buildImageURL()

