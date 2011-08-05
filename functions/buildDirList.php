<?php

// buildDirList() - Build list of directories for display.

function buildDirList ( $currDir, $maxColumns, $presorted, $ficons )
{
    global $mig_config;

    $oldCurrDir = $currDir;         // Stash this to build full path

    // Create a URL-encoded version of $currDir
    $enc_currdir = $currDir;
    $currDir = htmlentities(rawurldecode($enc_currdir));

    $directories = array ();                    // prototypes
    $counts = array ();
    $countdir = array ();
    $samples = array ();
    $filedates = array ();

    $x = $mig_config['albumdir'].'/'.$currDir;
    if (is_dir($x)) {
        // Open directory handle
        $dir = opendir($x);
    } else {
        print "ERROR: no such currDir '$currDir'<br>";
        exit;
    }

    while ($file = readdir($dir)) {

        // Only pay attention to directories
        $x = $mig_config['albumdir'].'/'.$currDir.'/'.$file;
        if (! is_dir($x)) {
            continue;
        }

        // Ignore . and ..
        if ($file == '.' || $file == '..') {
            continue;
        }

        // Ignore presorted items
        if ($presorted[$file]) {
            continue;
        }

        // Ignore directories whose name begins with "." if the
        // appropriate option is set
        if ($mig_config['ignoredotdirectories'] && preg_match('#^\.#', $file)) {
            continue;
        }

        // Ignore directories whose name does not match currDirNameRegexpr
        if (!preg_match($mig_config['currDirNameRegexpr'], $file)) {
            continue;
        }

        // If we got here, store it as a valid directory
        $directories[$file] = TRUE;

        // And stash a timestamp
        if (preg_match('#bydate.*#', $mig_config['foldersorttype'])) {
            $timestamp = filemtime($mig_config['albumdir'].'/'.$currDir
                                   .'/'.$file);
            $filedates["$timestamp-$file"] = $file;
        }
    }

    closedir($dir);

    // If we have directories, start a table
    if ($directories) {
        $directoryList .= "\n" . '   <table summary="Folder Links"'
                        . ' border="0" cellspacing="0"><tbody>';
    }

    ksort($directories);    // sort so we can yank them in sorted order
    reset($directories);    // reset array pointer to beginning

    if ($mig_config['foldersorttype'] == 'bydate-ascend') {
        ksort($filedates);
        reset($filedates);

    } elseif ($mig_config['foldersorttype'] == 'bydate-descend') {
        krsort($filedates);
        reset($filedates);
    }

    // Join the two sorted lists together into a single list
    if (preg_match('#bydate.*#', $mig_config['foldersorttype'])) {
        while (list($junk,$file) = each($filedates)) {
            $presorted[$file] = TRUE;
        }

    } else {
        while (list($file,$junk) = each($directories)) {
            $presorted[$file] = TRUE;
        }
    }

    // Make sure hidden items aren't displayed
    while (list($file,$junk) = each($mig_config['hidden']))
        unset ($presorted[$file]);

    reset($presorted);              // reset array pointers
    reset($mig_config['hidden']);

    // Iterate through all folders now that we have our final list.
    while (list($file,$junk) = each($presorted)) {

        $folder = $mig_config['albumdir'].'/'.$currDir.'/'.$file;

        // Calculate how many images in the folder if desired
        if ($mig_config['viewfoldercount']) {
            $counts[$file] = getNumberOfImages($folder);
            $countdir[$file] = getNumberOfDirs($folder, $currDir);
        }

        // Handle random folder thumbnails if desired
        if ($mig_config['randomfolderthumbs']) {
            $samples[$file] = getRandomThumb($file, $folder, $currDir);
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
        $linkURL = '<a href="'
                 . $mig_config['baseurl']
                 . '?pageType=folder&amp;currDir=' . $enc_file;
        if ($mig_config['mig_dl']) {
            $linkURL .= '&amp;mig_dl=' . $mig_config['mig_dl'];
        }
        $linkURL .= '">';

        // Reword $file so it doesn't allow wrapping of the label
        // (fixes odd formatting bug in MSIE).
        // Also, render _ as a space.
        // Also, shorten filename length if using random thumbs,
        // to make the table cleaner
        $nbspfile = $file;
        if ($mig_config['randomfolderthumbs']
            && strlen($nbspfile) > $mig_config['foldernamelength']) {
                $nbspfile = substr($nbspfile,0,$mig_config['foldernamelength']-1)
                          . '(..)';
        }
        $nbspfile = str_replace(' ', '&nbsp;', $nbspfile);
        $nbspfile = str_replace('_', '&nbsp;', $nbspfile);

        if ($mig_config['randomfolderthumbs']) {
            $folderTableClass = 'folderthumbs';
            $folderTableAlign = 'center';
        } else {
            $folderTableClass = 'foldertext';
            $folderTableAlign = 'left';
        }

        // Build the full link (icon plus folder name) and tack it on
        // the end of the list.
        $directoryList .= "\n" . '     <td valign="middle" class="'
                        . $folderTableClass . '" align="'
                        . $folderTableAlign . '">'
                        . $linkURL
                        . '<img src="';

        if ($mig_config['usethumbfile'][$file]) {
            // Found a UseThumb line in mig.cf - process as such

            $fname = getFileName($mig_config['usethumbfile'][$file]);
            if ($mig_config['thumbext']) {
                $fext = $mig_config['thumbext'];
            } else {
                $fext = getFileExtension($mig_config['usethumbfile'][$file]);
            }

            $directoryList .= $mig_config['albumurlroot'] . '/' . $currDir
                            . '/' . $file . '/';
            if ($mig_config['usethumbsubdir']) {
                $directoryList .= $mig_config['thumbsubdir'] . '/'
                                . $fname . '.' . $fext;
            } else {
                if ($mig_config['markertype'] == 'prefix') {
                    $directoryList .= $mig_config['markerlabel'] . '_' . $fname;
                } else {
                    $directoryList .= $fname . '_' . $mig_config['markerlabel'];
                }
                $directoryList .= '.' . $fext;
            }
        } elseif ($ficons[$file]) {
            // Found a FolderIcon line in mig.cf - process as such
            $directoryList .= $mig_config['imagedir'] . '/' . $ficons[$file];
        } elseif ($samples[$file]) {
            // Using a random thumbnail as the folder icon
            $directoryList .= $samples[$file];
        } else {
            // Otherwise, we're out a thumbnail; use the generic
            // folder icon as a last resort
            $directoryList .= $mig_config['imagedir'] . '/' . $mig_config['folder_icon'];
        }

        // Define a separator of either a space or a line break,
        // depending on whether we're using a random thumbnail or not.
        // (Use a line break if random thumbnail is present so the name
        // appears underneath it - also use a line break if the thumbnail
        // was specified).
        if ($samples[$file] || $mig_config['usethumbfile'][$file]) {
            $sep = '<br />';
        } else {
            $sep = '&nbsp;';
        }

        // Display _ as space
        $altlabel = str_replace('_', ' ', $file);

        // Output the rest of the link, label, etc.
        $directoryList .= '" '
                       . 'border="0" alt="' . $altlabel . '"/></a>' . $sep
                       . $linkURL . $nbspfile . '</a>';

        // Display counts if appropriate
        if ($mig_config['viewfoldercount'] &&
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
    if (!isset($directoryList) || $directoryList == '' || preg_match('#<tbody>$#', $directoryList)) {
        return 'NULL';
    } elseif (!preg_match('#</tr>$#i', $directoryList)) {
        // Stick a </tr> on the end if it isn't there already, and close
        // the table.
        $directoryList .= "\n   </tr>\n  </tbody></table>";
    } else {
        // Close the table.
        $directoryList .= "\n  </tbody></table>";
    }

    return $directoryList;

}   // -- End of buildDirList()

?>
