
// getRandomThumb() - Find a random thumbnail to show instead of the folder
//                    icon.

function getRandomThumb ( $file, $folder, $useThumbSubdir, $thumbSubdir,
                          $albumURLroot, $currDir )
{
    $ctr = 0;

    // Loop until we find what we're looking for
    while (! $mySample) {
        ++$ctr;
        if ($ctr > 20) {
            return 'images/folder.gif';
        }

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
                                         $albumURLroot,
                                         $currDir);

                        if ($mySample) {
                            return($mySample);
                        }
                    }
                }
                closedir($dirlist);
            }
        }
    }

}   // -- End of getRandomThumb()

