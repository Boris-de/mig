
// buildDirList() - creates list of directories available

function buildDirList ( $baseURL, $albumDir, $currDir, $imageDir,
                        $useThumbSubdir, $thumbSubdir, $maxColumns,
                        $hidden, $presorted, $viewFolderCount,
                        $markerType, $markerLabel, $ficons )
{

    $oldCurrDir = $currDir;         // Stash this to build full path with

    // Create a URL-encoded version of $currDir
    $enc_currdir = $currDir;
    $currDir = rawurldecode($enc_currdir);

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    $directories = array ();                    // prototypes
    $counts = array ();

    if ($viewFolderCount) {
        while(list($file,$x) = each($presorted)) {
            $folder = "$albumDir/$currDir/$file";
            $counts[$file] = getNumberOfImages($folder,
                                $useThumbSubdir, $markerType,
                                $markerLabel);
        }
        reset($presorted);
    }

    while ($file = readdir($dir)) {

        // Ignore . and .. and make sure it's a directory
        if ($file != '.' and $file != '..'
            and is_dir("$albumDir/$currDir/$file")) {

            // Ignore anything that's hidden or was already sorted.
            if (!$hidden[$file] and !$presorted[$file]) {

                // Stash file in an array
                $directories[$file] = TRUE;

                // Get a count of the images it contains, if
                // desired.
                if ($viewFolderCount) {
                    $folder = "$albumDir/$currDir/$file";
                    $counts[$file] = getNumberOfImages($folder,
                                        $useThumbSubdir, $markerType,
                                        $markerLabel);
                }
            }
        }
    }

    ksort($directories);    // sort so we can yank them in sorted order
    reset($directories);    // reset array pointer to beginning

    // snatch each element from $directories and shove it on the end of
    // $presorted
    while (list($file,$junk) = each($directories)) {
        $presorted[$file] = TRUE;
    }

    reset($presorted);          // reset array pointer

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
        $nbspfile = $file;
        $nbspfile = str_replace(' ', '&nbsp;', $nbspfile);
        $nbspfile = str_replace('_', '&nbsp;', $nbspfile);

        // Build the full link (icon plus folder name) and tack it on
        // the end of the list.
        $directoryList .= '<td class="folder">' . $linkURL . '<img src="'
                       . $imageDir . '/';
        if ($ficons[$file]) {
            $directoryList .= $ficons[$file];
        } else {
            $directoryList .= 'folder.gif';
        }
        $directoryList .= '" border="0"></a>&nbsp;'
                       . $linkURL . '<font size="-1">' . $nbspfile
                       . '</font></a>';
        if ($viewFolderCount and $counts[$file] > 0) {
            $directoryList .= ' (' . $counts[$file] . ')';
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

    closedir($dir); 

    // If there aren't any subfolders to look at, then just say so.
    if ($directoryList == '') {
        return 'NULL';

    } elseif (!eregi('</tr>$', $directoryList)) {

        // Stick a </tr> on the end if it isn't there already
        $directoryList .= '</tr>';
    }

    return $directoryList;

}   // -- End of buildDirList()

