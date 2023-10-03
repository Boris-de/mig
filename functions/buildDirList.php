<?php

// buildDirList() - Build list of directories for display.

function buildDirList ( $unsafe_currDir, $maxColumns, $presorted, $ficons )
{
    global $mig_config;

    $old_unsafe_CurrDir = $unsafe_currDir;         // Stash this to build full path

    // Create a URL-encoded version of $unsafe_currDir
    $unsafe_currDir = rawurldecode($unsafe_currDir);
    $enc_currDir = migHtmlSpecialChars($unsafe_currDir);

    $directories = array ();                    // prototypes
    $counts = array ();
    $countdir = array ();
    $samples = array ();
    $filedates = array ();

    $unsafe_abs_currDir = $mig_config['albumdir'].'/'.$unsafe_currDir;
    if (!is_dir($unsafe_abs_currDir)) {
        exit("ERROR: no such currDir '$enc_currDir'"); // should already be captured by body.php...
    }

    $dir = opendir($unsafe_abs_currDir);
    while ($file = readdir($dir)) {
        if ($file == '.' || $file == '..') {
            continue; // skip self and parent
        }

        // Only pay attention to directories
        $unsafe_abs_childDir = $unsafe_abs_currDir.'/'.$file;
        if (!is_dir($unsafe_abs_childDir)) {
            continue;
        }

        // Ignore presorted items
        if (isset($presorted[$file])) {
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
            $timestamp = filemtime($unsafe_abs_childDir);
            $filedates["$timestamp-$file"] = $file;
        }
    }

    closedir($dir);

    $directoryList = '';

    // If we have directories, start a table
    if ($directories) {
        $directoryList .= "\n" . '   <table summary="Folder Links"'
                        . ' border="0" cellspacing="0"><tbody>';
    }

    ksort($directories);    // sort so we can yank them in sorted order

    if ($mig_config['foldersorttype'] == 'bydate-ascend') {
        ksort($filedates);

    } elseif ($mig_config['foldersorttype'] == 'bydate-descend') {
        krsort($filedates);
    }

    // Join the two sorted lists together into a single list
    if (preg_match('#bydate.*#', $mig_config['foldersorttype'])) {
        foreach (array_values($filedates) as $file) {
            $presorted[$file] = TRUE;
        }

    } else {
        foreach (array_keys($directories) as $file) {
            $presorted[$file] = TRUE;
        }
    }

    // Make sure hidden items aren't displayed
    foreach (array_keys($mig_config['hidden']) as $file) {
        unset ($presorted[$file]);
    }

    // Iterate through all folders now that we have our final list.
    foreach (array_keys($presorted) as $file) {

        $unsafe_folder = $mig_config['albumdir'].'/'.$unsafe_currDir.'/'.$file;

        // Calculate how many images in the folder if desired
        if ($mig_config['viewfoldercount']) {
            $counts[$file] = getNumberOfImages($unsafe_folder);
            $countdir[$file] = getNumberOfDirs($unsafe_folder);
        }

        // Handle random folder thumbnails if desired
        if ($mig_config['randomfolderthumbs']) {
            $samples[$file] = getRandomThumb($file, $unsafe_folder, $unsafe_currDir);
        }
    }

    // Track columns
    $col = 0;
    --$maxColumns;  // Tricks $maxColumns into working since it
                    // really starts at 0, not 1

    foreach (array_keys($presorted) as $file) {

        // Start a new row if appropriate
        if ($col == 0) {
            $directoryList .= "\n   <tr>";
        }

        // Surmise the full path to work with
        $unsafe_newCurrDir = $old_unsafe_CurrDir . '/' . $file;

        // URL-encode the directory name in case it contains spaces
        // or other weirdness.
        $enc_file = migURLencode($unsafe_newCurrDir);

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
        $nbspfile = strtr(migHtmlSpecialChars($nbspfile), array(' ' => '&nbsp;', '_' => '&nbsp;'));

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

        if (!empty($mig_config['usethumbfile'][$file])) {
            // Found a UseThumb line in mig.cf - process as such

            $fname = getFileName($mig_config['usethumbfile'][$file]);
            if ($mig_config['thumbext']) {
                $fext = $mig_config['thumbext'];
            } else {
                $fext = getFileExtension($mig_config['usethumbfile'][$file]);
            }

            $directoryList .= $mig_config['albumurlroot'] . '/' . $enc_currDir
                            . '/' . migHtmlSpecialChars($file) . '/';
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
        } elseif (isset($ficons[$file])) {
            // Found a FolderIcon line in mig.cf - process as such
            $directoryList .= $mig_config['imagedir'] . '/' . $ficons[$file];
        } elseif (isset($samples[$file])) {
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
        if (isset($samples[$file]) || !empty($mig_config['usethumbfile'][$file])) {
            $sep = '<br />';
        } else {
            $sep = '&nbsp;';
        }

        // Display _ as space
        $altlabel = strtr(migHtmlSpecialChars($file), '_', ' ');

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
            $col = 0;
        } else {
            ++$col;
        }
    }

    // If there aren't any subfolders to look at, then just say so.
    if ($directoryList == '' || preg_match('#<tbody>$#', $directoryList)) {
        return '';
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
