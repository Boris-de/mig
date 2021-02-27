<?php

// getRandomThumb() - Find a random thumbnail to show instead of the folder icon.

function getRandomThumb ( $file, $unsafe_folder, $unsafe_currDir )
{
    global $mig_config;
    
    $markerLabel = $mig_config['markerlabel'];

    // SECTION ONE ...
    // If we're using thumbnail subdirectories.

    $randThumbList = array ();          // initialize
    if ($mig_config['usethumbsubdir']) {
        $unsafe_thumb_dir = $unsafe_folder . '/' . $mig_config['thumbsubdir'];

        // Does the thumb subdir exist?  Why would it not?  It would not
        // if we're in a folder that contains only other folders, for
        // example.  Or it might just not exist because no one made thumbs,
        // in which case we can't show any thumbs anyway.
        if (is_dir($unsafe_thumb_dir)) {
            $readSample = opendir($unsafe_thumb_dir);

            // Read each item in the directory...
            while ($sample = readdir($readSample)) {
                if ($sample == '.' || $sample == '..') {
                    continue; // skip self and parent
                }

                // Ignore hidden items
                if ($mig_config['hidden'][$sample]) {
                    continue;
                }

                // And use the first valid match found
                if (getFileType($sample)) {
                    $mySample = $mig_config['albumurlroot'] . '/'
                              . migURLencode($unsafe_currDir)
                              . '/' . migURLencode($file)
                              . '/' .$mig_config['thumbsubdir']
                              . '/' . $sample;

                    // If "real rand" is in use, add this to the
                    // list.  Otherwise just return what we found.
                    if ($mig_config['userealrandthumbs']) {
                        $randThumbList[] = $mySample;
                    } else {
                        return $mySample;
                    }
                }
            }
            closedir($readSample);

        } elseif (is_dir($unsafe_folder)) {

            // No thumb subdir exists, although $useThumbSubdir
            // is set TRUE.  We're either in a folder which has no
            // thumbs generated, or we're in a folder which contains
            // only other folders.  Iterate through items to find
            // a folder and grab an item from THAT folder, if one
            // can be found.
            //
            // This should be able to drill down as far as necessary
            // until a valid thumb is found.

            $dirlist = opendir($unsafe_folder);
            $subfList = array ();

            while ($item = readdir($dirlist)) {
                if ($item == '.' || $item == '..') {
                    continue; // skip self and parent
                }

                if (is_dir("$unsafe_folder/$item")) {

                    // Ignore hidden items
                    if ($mig_config['hidden'][$item]) {
                        continue;
                    }

                    // Ignore dot directories if appropriate
                    if ($mig_config['ignoredotdirectories'] && preg_match('#^\.#', $item)) {
                        continue;
                    }

                    // If using "real rand" create a list of folders
                    // and pick a random folder, then recurse into it.
                    // Otherwise just use the first folder found,
                    // and recurse into that.
                    if ($mig_config['userealrandthumbs']) {
                        $subfList[] = $item;
                    } else {
                        $mySample = getRandomThumb($file.'/'.$item, $unsafe_folder.'/'.$item, $unsafe_currDir);

                        if ($mySample) {
                            return $mySample;
                        }
                    }
                }
            }
            closedir($dirlist);

            if (!empty($subfList)) {
                $randval = rand(0,(sizeof($subfList)-1)); // get random folder
                $mySample = getRandomThumb($file.'/'.$subfList[$randval],
                                 $unsafe_folder.'/'.$subfList[$randval], $unsafe_currDir);

                return $mySample;
            }
        }

    // SECTION TWO...
    // Not using thumbnail subdirectories

    } else {

        if (!is_dir($unsafe_folder)) {
            // If it's not a directory, just bail out now.
            return FALSE;
        }

        // Open $folder as a directory handle
        $readSample = opendir($unsafe_folder);

        // Iterate through all files in this folder...
        while ($sample = readdir($readSample)) {
            if ($sample == '.' || $sample == '..') {
                continue; // skip self and parent
            }

            $mySample = NULL;    // Cleanup from last loop iteration

            // Using prefix/suffix and label settings,
            // figure out if this is a thumbnail or not.
            // This is so we skip over regular images.
            if ($mig_config['markertype'] == 'prefix') {
                if (preg_match("#^$markerLabel\_#", $sample)
                    && getFileType($sample))
                {
                    $mySample = $sample;
                }
            } elseif ($mig_config['markertype'] == 'suffix') {
                if (preg_match("#_$markerLabel\.[^.]+$#", $sample)
                    && getFileType($sample))
                {
                    $mySample = $sample;
                }

            } else {
                print 'ERROR: no markerType set in getRandomThumb()';
                exit;
            }

            if ($mySample) {
                $mySample = $mig_config['albumurlroot'] . '/' . $unsafe_currDir . '/' . $file . '/' . $mySample;

                // If "real rand" is in effect, add to the list for
                // later random selection.  Otherwise just return
                // what we found.
                if ($mig_config['userealrandthumbs']) {
                    $randThumbList[] = $mySample;
                } else {
                    return $mySample;
                }

            } else {
                $dirlist = opendir($unsafe_folder);
                $subfList = array ();
                while ($item = readdir($dirlist)) {
                    if ($item == '.' || $item == '..') {
                        continue; // skip self and parent
                    }

                    if (is_dir("$unsafe_folder/$item")) {

                        // Ignore hidden items
                        if ($mig_config['hidden'][$item]) {
                            continue;
                        }

                        // Ignore dot directories if appropriate
                        if ($mig_config['ignoredotdirectories'] && preg_match('#^\.#', $item)) {
                            continue;
                        }

                        if ($mig_config['userealrandthumbs']) {
                            $subfList[] = $item;
                        } else {
                            $mySample = getRandomThumb($file.'/'.$item, $unsafe_folder.'/'.$item, $unsafe_currDir);

                            if ($mySample) {
                                return $mySample;
                            }
                        }
                    }
                }
                closedir($dirlist);

                if (isset($subfList[0])) {
                    $randval = rand(0,(sizeof($subfList)-1)); // get random folder
                    $mySample = getRandomThumb($file.'/'.$subfList[$randval],
                                        $unsafe_folder.'/'.$subfList[$randval], $unsafe_currDir);

                    if ($mySample) {
                        return $mySample;
                    }
                }
            }
        }
        
        closedir($readSample);
    }

    if ($randThumbList) {
        $randval = rand(0,(sizeof($randThumbList)-1)); // choose random thumb
        return $randThumbList[$randval];
    } else {
        return FALSE;
    }

}   // -- End of getRandomThumb()

?>