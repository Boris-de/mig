<?php

// getRandomThumb() - Find a random thumbnail to show instead of the folder
//                    icon.

function getRandomThumb ( $file, $folder,
                          $albumURLroot, $currDir, $markerType, $markerLabel,
                          $useRealRandThumbs, $ignoreDotDirectories )
{
    global $hidden;
    global $mig_config;

    // I don't know why this was here but it broke layout badly...
    //print "<br>";

    // SECTION ONE ...
    // If we're using thumbnail subdirectories.

    if ($mig_config["usethumbsubdir"]) {
        $myThumbDir = $folder . "/" . $mig_config["thumbsubdir"];

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
                if ($sample != "." && $sample != "..") {

                    // Ignore hidden items
                    if ($hidden[$sample]) {
                        continue;
                    }

                    // And use the first valid match found
                    if (getFileType($sample)) {
                        $mySample = $albumURLroot . "/"
                                  . migURLencode($currDir)
                                  . "/" . migURLencode($file)
                                  . "/" .$mig_config["thumbsubdir"]
                                  . "/" . $sample;

                        // If "real rand" is in use, add this to the
                        // list.  Otherwise just return what we found.
                        if ($useRealRandThumbs) {
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
                if (is_dir("$folder/$item") && $item != "."
                            && $item != "..")
                {

                    // Ignore hidden items
                    if ($hidden[$item]) {
                        continue;
                    }

                    // Ignore dot directories if appropriate
                    if ($ignoreDotDirectories && ereg("^\.", $item)) {
                        continue;
                    }

                    // If using "real rand" create a list of folders
                    // and pick a random folder, then recurse into it.
                    // Otherwise just use the first folder found,
                    // and recurse into that.
                    if ($useRealRandThumbs) {
                        $subfList[] = $item;
                    } else {
                        $mySample = getRandomThumb($file."/".$item,
                                       $folder."/".$item,
                                       $albumURLroot, $currDir,
                                       $markerType, $markerLabel,
                                       $useRealRandThumbs,
                                       $ignoreDotDirectories);

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
                $mySample = getRandomThumb($file."/".$subfList[$randval],
                                    $folder."/".$subfList[$randval],
                                    $albumURLroot, $currDir,
                                    $markerType, $markerLabel,
                                    $useRealRandThumbs,
                                    $ignoreDotDirectories);

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
            if ($markerType == "prefix") {
                if (ereg("^$markerLabel\_", $sample)
                    && getFileType($sample))
                {
                    $mySample = $sample;
                }
            } elseif ($markerType == "suffix") {
                if (ereg("_$markerLabel\.[^.]+$", $sample)
                    && getFileType($sample))
                {
                    $mySample = $sample;
                }

            } else {
                print "ERROR: no markerType set in getRandomThumb()";
                exit;
            }

            if ($mySample) {
                $mySample = $albumURLroot . "/" . $currDir . "/" . $file
                          . "/" . $mySample;

                // If "real rand" is in effect, add to the list for
                // later random selection.  Otherwise just return
                // what we found.
                if ($useRealRandThumbs) {
                    $randThumbList[] = $mySample;
                } else {
                    return $mySample;
                }

            } else {
                $dirlist = opendir($folder);
                $subfList = array ();
                while ($item = readdir($dirlist)) {
                    if (is_dir("$folder/$item") && $item != "."
                               && $item != "..")
                    {

                        // Ignore hidden items
                        if ($hidden[$item]) {
                            continue;
                        }

                        // Ignore dot directories if appropriate
                        if ($ignoreDotDirectories && ereg("^\.", $item)) {
                            continue;
                        }

                        if ($useRealRandThumbs) {
                            $subfList[] = $item;
                        } else {
                            $mySample = getRandomThumb($file."/".$item,
                                            $folder."/".$item,
                                            $albumURLroot, $currDir,
                                            $markerType, $markerLabel,
                                            $useRealRandThumbs,
                                            $ignoreDotDirectories);

                            if ($mySample) {
                                return $mySample;
                            }
                        }
                    }
                }
                closedir($dirlist);

                if ($subfList[0]) {
                    srand((double)microtime()*1000000); 		// get random folder
                    $randval = rand(0,(sizeof($subfList)-1));
                    $mySample = getRandomThumb($file."/".$subfList[$randval],
                                        $folder."/".$subfList[$randval],
                                        $albumURLroot, $currDir,
                                        $markerType, $markerLabel,
                                        $useRealRandThumbs,
                                        $ignoreDotDirectories);

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
