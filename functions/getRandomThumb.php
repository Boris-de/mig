
// getRandomThumb() - Find a random thumbnail to show instead of the folder
//                    icon.

function getRandomThumb ( $file, $folder, $useThumbSubdir, $thumbSubdir,
                          $albumURLroot, $currDir, $markerType, $markerLabel )
{
    // SECTION ONE ...
    // If we're using thumbnail subdirectories

    if ($useThumbSubdir) {
        $myThumbDir = $folder . '/' . $thumbSubdir;

        if (file_exists($myThumbDir)) {
            if (is_dir($myThumbDir)) {
                $readSample = opendir($myThumbDir);
                while ($sample = readdir($readSample)) {
                    if ($sample != '.' && $sample != '..') {
                        if (validFileType($sample)) {
                            $mySample = $albumURLroot . '/' . $currDir
                                      . '/' . $file . '/' .$thumbSubdir
                                      . '/' . $sample;
                            return($mySample);
                        }
                    }
                }
                closedir($readSample);
            }
        } else {
            $dirlist = opendir($folder);
            while ($item = readdir($dirlist)) {
                if (is_dir("$folder/$item") && $item != '.'
                            && $item != '..')
                {
                    $mySample = getRandomThumb($file.'/'.$item,
                                     $folder.'/'.$item,
                                     $useThumbSubdir, $thumbSubdir,
                                     $albumURLroot, $currDir,
                                     $markerType, $markerLabel);

                    if ($mySample) {
                        return($mySample);
                    }
                }
            }
            closedir($dirlist);
        }

    // SECTION TWO...
    // Not using thumbnail subdirectories

    } else {

        $readSample = opendir($folder);

        while ($sample = readdir($readSample)) {
            if ($markerType == 'prefix') {
                if (ereg("^$markerLabel\_", $sample)
                    && validFileType($sample))
                {
                    $mySample = $sample;
                }
            } elseif ($markerType == 'suffix') {
                if (ereg("_$markerLabel\.[^.]+$", $sample)
                    && validFileType($sample))
                {
                    $mySample = $sample;
                }

            } else {
                print 'ERROR: no markerType set in getRandomThumb()';
                exit;
            }

            if ($mySample) {
                $mySample = $albumURLroot . '/' . $currDir . '/' . $file
                          . '/' . $mySample;
                return($mySample);
            } else {
                $dirlist = opendir($folder);
                while ($item = readdir($dirlist)) {
                    if (is_dir("$folder/$item") && $item != '.'
                               && $item != '..')
                    {
                        $mySample = getRandomThumb($file.'/'.$item,
                                        $folder.'/'.$item,
                                        $useThumbSubdir, $thumbSubdir,
                                        $albumURLroot, $currDir,
                                        $markerType, $markerLabel);

                        if ($mySample) {
                            return($mySample);
                        }
                    }
                }
                closedir($dirlist);
            }
        }
        
        closedir($readSample);
    }

    return FALSE;

}   // -- End of getRandomThumb()

