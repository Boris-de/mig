
// getExifDescription() - Fetches a comment if available from the
// Exif comments file (exif.inf) as well as fetching EXIF data

function getExifDescription( $albumDir, $currDir, $image, $viewCamInfo,
                             $viewDateInfo)
{
    global $mig_config;

    $desc = array ();
    $model = array ();
    $shutter = array ();
    $aperture = array ();
    $foclen = array ();
    $flash = array ();
    $iso = array ();
    $timestamp = array ();

    if (file_exists("$albumDir/$currDir/exif.inf")) {

        $file = fopen("$albumDir/$currDir/exif.inf", 'r');
        $line = fgets($file, 4096);     // get first line
        while (!feof($file)) {

            if (ereg('^File name    : ', $line)) {
                $fname = ereg_replace('^File name    : ', '', $line);
                $fname = chop($fname);

            } elseif (ereg('^Comment      : ', $line)) {
                $comment = ereg_replace('^Comment      : ', '', $line);
                $comment = chop($comment);
                $desc[$fname] = $comment;

            }

            if ($viewCamInfo) {
            
                if (ereg('^Camera model : ', $line)) {
                    $x = ereg_replace('^Camera model : ', '', $line);
                    $x = chop($x);
                    $model[$fname] = $x;

                // This one apparently sometimes has a space after
                // the colon, sometimes not.  Try to work either way.
                } elseif (ereg('^Exposure time: ?', $line)) {
                    $x = ereg_replace('^Exposure time: ?', '', $line);
                    if (ereg('\(', $x)) {
                        $x = ereg_replace('^.*\(', '', $x);
                        $x = ereg_replace('\).*$', '', $x);
                    }
                    $x = chop($x);
                    $shutter[$fname] = $x;

                } elseif (ereg('^Aperture     : ', $line)) {
                    $x = ereg_replace('^Aperture     : ', '', $line);
                    // make it fN.N instead of f/N.N
                    $x = ereg_replace('/', '', $x);
                    $x = chop($x);
                    $aperture[$fname] = $x;

                } elseif (ereg('^Focal length : ', $line)) {
                    $x = ereg_replace('^Focal length : ', '', $line);
                    if (ereg('35mm equiv', $x)) {
                        $x = ereg_replace('^.*alent: ', '', $x);
                        $x = chop($x);
                        $x = ereg_replace('\)$', '', $x);
                    }
                    $foclen[$fname] = $x;

                } elseif (ereg('^ISO equiv.   : ', $line)) {
                    $x = ereg_replace('ISO equiv.   : ', '', $line);
                    $x = chop($x);
                    $iso[$fname] = $x;

                } elseif (ereg('^Flash used   : Yes', $line)) {
                    $flash[$fname] = TRUE;

                } elseif (ereg('^Date/Time    : ', $line)) {
                    $x = ereg_replace('Date/Time    : ', '', $line);
                    $x = chop($x);

                    // Turn into human readable format and record
                    $timestamp[$fname] = parseExifDate($x);
                }
            }

            $line = fgets($file, 4096);
        }

        fclose($file);

        $return = '';
        if ($desc[$image]) {
            $return .= $desc[$image];
        }

        if ($viewCamInfo and $model[$image]) {

            $return .= '<i>';
            if ($viewDateInfo) {
                $return .= $timestamp[$image] .' - ';
            }
            $return .= $model[$image] . '<br>';
            if ($iso[$image]) {
                $return .= 'ISO ' . $iso[$image] . ', ';
            }
            $return .= $foclen[$image] . ' ';
            $return .= $shutter[$image] . ' ';
            $return .= $aperture[$image];
            if ($flash[$image]) {
                $return .= ' ('
                        . $mig_config['lang']['flash_used']
                        . ')';
            }
        }

        return $return;

    } else {
        return '';
    }

}   // -- End of getExifDescription()

