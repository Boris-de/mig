
// buildDirList() - creates list of directories available

function buildDirList ( $baseURL, $albumDir, $albumURLroot, $currDir,
                        $imageDir, $useThumbSubdir, $thumbSubdir,
                        $maxColumns, $hidden, $presorted, $viewFolderCount,
                        $markerType, $markerLabel, $ficons,
                        $randomFolderThumbs, $folderNameLength,
                        $useThumbFile, $ignoreDotDirectories )
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

    if (is_dir("$albumDir/$currDir")) {
        $dir = opendir("$albumDir/$currDir");   // Open directory handle
    } else {
        print "ERROR: no such currDir '$currDir'<br>";
        exit;
    }

    while ($file = readdir($dir)) {

        // Only pay attention to directories
        if (! is_dir("$albumDir/$currDir/$file"))
            continue;

        // Ignore . and ..
        if ($file == '.' || $file == '..')
            continue;

        // Ignore presorted items
        if ($presorted[$file])
            continue;

        // Ignore directories whose name begins with '.' if the
        // appropriate option is set
        if ($ignoreDotDirectories && ereg('^\.', $file))
            continue;

        // If we got here, store it as a valid directory
        $directories[$file] = TRUE;
    }

    closedir($dir);

    // If we have directories, start a table
    if ($directories) {
        $directoryList .= "\n" . '   <table summary="Folder Links"'
                        . ' border="0" cellspacing="0"><tbody>';
    }

    ksort($directories);    // sort so we can yank them in sorted order
    reset($directories);    // reset array pointer to beginning

    // snatch each element from $directories and shove it on the end of
    // $presorted
    while (list($file,$junk) = each($directories)) {
        $presorted[$file] = TRUE;
    }

    // Make sure hidden items aren't displayed
    while (list($file,$junk) = each($hidden))
        unset ($presorted[$file]);

    reset($presorted);          // reset array pointer

    // Iterate through all folders now that we have our final list.
    while (list($file,$junk) = each($presorted)) {

        $folder = "$albumDir/$currDir/$file";

        // Calculate how many images in the folder if desired
        if ($viewFolderCount) {
            $counts[$file] = getNumberOfImages($folder, $useThumbSubdir,
                                               $markerType, $markerLabel);
            $countdir[$file] = getNumberOfDirs($folder, $useThumbSubdir,
                                               $thumbSubdir, $markerType,
                                               $markerLabel);
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
    --$maxColumns;  // Tricks $maxColumns into working since it
                    // really starts at 0, not 1

    while (list($file,$junk) = each($presorted)) {

        // Start a new row if appropriate
        if ($col == 0) {
            $directoryList .= "\n   <tr>";
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

        if ($randomFolderThumbs) {
            $folderTableClass = 'folderthumbs';
            $folderTableAlign = 'center';
        } else {
            $folderTableClass = 'foldertext';
            $folderTableAlign = 'left';
        }

        // Build the full link (icon plus folder name) and tack it on
        // the end of the list.
        $directoryList .= "\n     " . '<td valign="middle" class="'
                        . $folderTableClass . '" align="'
                        . $folderTableAlign . '">' . $linkURL
                        . '<img src="';

        if ($useThumbFile[$file]) {
            // Found a UseThumb line in mig.cf - process as such

            $fname = getFileName($useThumbFile[$file]);
            if ($thumbExt) {
                $fext = $thumbExt;
            } else {
                $fext = getFileExtension($useThumbFile[$file]);
            }

            $directoryList .= $albumURLroot . '/' . $currDir
                            . '/' . $file . '/';
            if ($useThumbSubdir) {
                $directoryList .= $thumbSubdir . '/' . $fname . '.' . $fext;
            } else {
                if ($markerType == 'prefix') {
                    $directoryList .= $markerLabel . '_' . $fname;
                } else {
                    $directoryList .= $fname . '_' . $markerLabel;
                }
                $directoryList .= '.' . $fext;
            }
        } elseif ($ficons[$file]) {
            // Found a FolderIcon line in mig.cf - process as such
            $directoryList .= $imageDir . '/' . $ficons[$file];
        } elseif ($samples[$file]) {
            // Using a random thumbnail as the folder icon
            $directoryList .= $samples[$file];
        } else {
            // Otherwise, we're out a thumbnail; use the generic
            // folder icon as a last resort
            $directoryList .= $imageDir . '/folder.gif';
        }

        // Define a separator of either a space or a line break,
        // depending on whether we're using a random thumbnail or not.
        // (Use a line break if random thumbnail is present so the name
        // appears underneath it - also use a line break if the thumbnail
        // was specified).
        if ($samples[$file] || $useThumbFile[$file]) {
            $sep = '<br />';
        } else {
            $sep = '&nbsp;';
        }

        // Display _ as space
        $altlabel = str_replace('_', ' ', $file);

        // Output the rest of the link, label, etc.
        $directoryList .= '" border="0" alt="' . $altlabel . '"></a>' . $sep
                       . $linkURL . $nbspfile . '</a>';

        // Display counts if appropriate
        if ($viewFolderCount &&
                (($counts[$file] > 0) || ($countdir[$file] > 0)) )
        {
            $directoryList .= $sep . '<acronym title="(folders/files)">('
                            . $countdir[$file] . '/' . $counts[$file]
                            . ')</acronym>';
        }

        // Don't forget to close the table cell
        $directoryList .= '</td>';

        // Keep track of what row/column we're on
        if ($col == $maxColumns) {
            $directoryList .= "\n   </tr>";
            ++$row;
            $col = 0;
        } else {
            ++$col;
        }
    }

    // If there aren't any subfolders to look at, then just say so.
    if ($directoryList == '') {
        return 'NULL';
    } elseif (!eregi('</tr>$', $directoryList)) {
        // Stick a </tr> on the end if it isn't there already, and close
        // the table.
        $directoryList .= "\n   </tr>\n  </tbody></table>";
    } else {
        // Close the table.
        $directoryList .= "\n  </tbody></table>";
    }

    return $directoryList;

}   // -- End of buildDirList()

