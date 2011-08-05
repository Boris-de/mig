<?php

// getRandomThumb() - Find a random thumbnail to show instead of the folder icon.

function getRandomThumb ( $file, $folder, $currDir )
{
    global $mig_config;
    
    $markerLabel = $mig_config['markerlabel'];

    // SECTION ONE ...
    // If we're using thumbnail subdirectories.

    if ($mig_config['usethumbsubdir']) {
        $myThumbDir = $folder . '/' . $mig_config['thumbsubdir'];

        // Does the thumb subdir exist?  Why would it not?  It would not
        // if we're in a folder that contains only other folders, for
        // example.  Or it might just not exist because no one made thumbs,
        // in which case we can't show any thumbs anyway.
        if (is_dir($myThumbDir)) {
            $readSample = opendir($myThumbDir);
            $randThumbList = array ();          // initialize

            // Read each item in the directory...
            while ($sample = readdir($readSample)) {
                // Ignoring . and ..
                if ($sample != '.' && $sample != '..') {

                    // Ignore hidden items
                    if ($mig_config['hidden'][$sample]) {
                        continue;
                    }

                    // And use the first valid match found
                    if (getFileType($sample)) {
                        $mySample = $mig_config['albumurlroot'] . '/'
                                  . migURLencode($currDir)
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
            }
            closedir($readSample);

        } elseif (is_dir($folder)) {

            // No thumb subdir exists, although $useThumbSubdir
            // is set TRUE.  We're either in a folder which has no
            // thumbs generated, or we're in a folder which contains
            // only other folders.  Iterate through items to find
            // a folder and grab an item from THAT folder, if one
            // can be found.
            //
            // This should be able to drill down as far as necessary
            // until a valid thumb is found.

            $dirlist = opendir($folder);
            $subfList = array ();

            while ($item = readdir($dirlist)) {
                if (is_dir("$folder/$item") && $item != '.'
                            && $item != '..')
                {

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
                        $mySample = getRandomThumb($file.'/'.$item, $folder.'/'.$item,
                                                   $currDir,
                                                   $mig_config['userealrandthumbs']);

                        if ($mySample) {
                            return $mySample;
                        }
                    }
                }
            }
            closedir($dirlist);

            if ($subfList[0]) {
                srand((double)microtime()*1000000); // get random folder
                $randval = rand(0,(sizeof($subfList)-1));
                $mySample = getRandomThumb($file.'/'.$subfList[$randval],
                                    $folder.'/'.$subfList[$randval], $currDir);

                return $mySample;
            }
        }

    // SECTION TWO...
    // Not using thumbnail subdirectories

    } else {

        if (is_dir($folder)) {
            // Open $folder as a directory handle
            $readSample = opendir($folder);
        } else {
            // If it's not a directory, just bail out now.
            return FALSE;
        }

        // Iterate through all files in this folder...
        while ($sample = readdir($readSample)) {

            unset($mySample);    // Cleanup from last loop iteration

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
                $mySample = $mig_config['albumurlroot'] . '/' . $currDir . '/' . $file
                          . '/' . $mySample;

                // If "real rand" is in effect, add to the list for
                // later random selection.  Otherwise just return
                // what we found.
                if ($mig_config['userealrandthumbs']) {
                    $randThumbList[] = $mySample;
                } else {
                    return $mySample;
                }

            } else {
                $dirlist = opendir($folder);
                $subfList = array ();
                while ($item = readdir($dirlist)) {
                    if (is_dir("$folder/$item") && $item != '.'
                               && $item != '..')
                    {

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
                            $mySample = getRandomThumb($file.'/'.$item, $folder.'/'.$item,
                                            $currDir);

                            if ($mySample) {
                                return $mySample;
                            }
                        }
                    }
                }
                closedir($dirlist);

                if ($subfList[0]) {
                    srand((double)microtime()*1000000);     // get random folder
                    $randval = rand(0,(sizeof($subfList)-1));
                    $mySample = getRandomThumb($file.'/'.$subfList[$randval],
                                        $folder.'/'.$subfList[$randval], $currDir);

                    if ($mySample) {
                        return $mySample;
                    }
                }
            }
        }
        
        closedir($readSample);
    }

    if ($randThumbList) {
        srand((double)microtime()*1000000);   // choose random thumb
        $randval = rand(0,(sizeof($randThumbList)-1));
        return $randThumbList[$randval];
    } else {
        return FALSE;
    }

}   // -- End of getRandomThumb()

?>