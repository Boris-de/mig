
// buildDirList() - creates list of directories available

function buildDirList ( $baseURL, $albumDir, $albumURLroot, $currDir,
                        $imageDir, $useThumbSubdir, $thumbSubdir,
                        $maxColumns, $hidden, $presorted, $viewFolderCount,
                        $markerType, $markerLabel, $ficons,
                        $randomFolderThumbs, $folderNameLength )
{
    global $mig_config;

    $oldCurrDir = $currDir;         // Stash this to build full path

    // Create a URL-encoded version of $currDir
    $enc_currdir = $currDir;
    $currDir = rawurldecode($enc_currdir);

    $directories = array ();                    // prototypes
    $counts = array ();
    $countdir = array ();
    $samples = array ();

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    while ($file = readdir($dir)) {

        // Ignore . and .. and make sure it's a directory
        if ($file != '.' && $file != '..'
            && is_dir("$albumDir/$currDir/$file")) {

            // Ignore anything that's hidden or was already sorted.
            if (! $hidden[$file] && ! $presorted[$file]) {
                // Stash file in an array
                    $directories[$file] = TRUE;
            }
        }
    }

    closedir($dir);

    ksort($directories);    // sort so we can yank them in sorted order
    reset($directories);    // reset array pointer to beginning

    // snatch each element from $directories and shove it on the end of
    // $presorted
    while (list($file,$junk) = each($directories)) {
        $presorted[$file] = TRUE;
    }

    reset($presorted);          // reset array pointer

    // Iterate through all folders now that we have our final list.
    while (list($file,$junk) = each($presorted)) {

        $folder = "$albumDir/$currDir/$file";

        // Calculate how many images in the folder if desired
        if ($viewFolderCount) {
            $counts[$file] = getNumberOfImages($folder, $useThumbSubdir,
                                               $markerType, $markerLabel);
            $countdir[$file] = getNumberOfDirs($folder, $useThumbSubdir,
                                               $markerType, $markerLabel);
        }

        // Handle random folder thumbnails if desired
        if ($randomFolderThumbs) {
            $samples[$file] = getRandomThumb($file, $folder, $useThumbSubdir,
                                             $thumbSubdir, $albumURLroot,
                                             $currDir, $markerType,
                                             $markerLabel);
        }
    }

    reset($presorted);

    // Track columns
    $row = 0;
    $col = 0;
    $maxColumns--;  // Tricks $maxColumns into working since it
                    // really starts at 0, not 1

    while (list($file,$junk) = each($presorted)) {

        // Start a new row if appropriate
        if ($col == 0) {
            $directoryList .= '<tr>';
        }

        // Surmise the full path to work with
        $newCurrDir = $oldCurrDir . '/' . $file;

        // URL-encode the directory name in case it contains spaces
        // or other weirdness.
        $enc_file = migURLencode($newCurrDir);

        // Build the link itself for re-use below
        $linkURL = '<a href="' . $baseURL
                 . '?pageType=folder&currDir=' . $enc_file . '">';

        // Reword $file so it doesn't allow wrapping of the label
        // (fixes odd formatting bug in MSIE).
        // Also, render _ as a space.
        // Also, shorten filename length if using random thumbs,
        // to make the table cleaner
        $nbspfile = $file;
        if ($randomFolderThumbs && strlen($nbspfile) > $folderNameLength) {
            $nbspfile = substr($nbspfile,0,$folderNameLength-1) . '(..)';
        }
        $nbspfile = str_replace(' ', '&nbsp;', $nbspfile);
        $nbspfile = str_replace('_', '&nbsp;', $nbspfile);

        // Build the full link (icon plus folder name) and tack it on
        // the end of the list.
        $directoryList .= '<td valign="bottom" class="folder">'
                        . $linkURL . '<img src="';
        if ($ficons[$file]) {
            $directoryList .= $ficons[$file];
        } elseif ($samples[$file]) {
            $directoryList .= $samples[$file];
        } else {
            $directoryList .= $imageDir . '/folder.gif';
        }

        if (! $samples[$file]) {
            $sep = '&nbsp;';
        } else {
            $sep = '<br>';
        }

        $altlabel = str_replace('_', ' ', $file);
        $directoryList .= '" border="0" alt="' . $altlabel . '"></a>' . $sep
                       . $linkURL . '<font size="-2">' . $nbspfile
                       . '</font></a>';

        if ($viewFolderCount &&
                (($counts[$file] > 0) || ($countdir[$file] > 0)) )
        {
            $directoryList .= $sep . '(' . $countdir[$file] . '/'
                            . $counts[$file] . ')';
        }

        $directoryList .= '</td>';

        // Keep track of what row/column we're on
        if ($col == $maxColumns) {
            $directoryList .= '</tr>';
            $row++;
            $col = 0;
        } else {
            $col++;
        }
    }

    // If there aren't any subfolders to look at, then just say so.
    if ($directoryList == '') {
        return 'NULL';
    } elseif (!eregi('</tr>$', $directoryList)) {
        // Stick a </tr> on the end if it isn't there already
        $directoryList .= '</tr>';
    }

    return $directoryList;

}   // -- End of buildDirList()

