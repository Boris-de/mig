
// getExifDescription() - Fetches a comment if available from the
//                        Exif comments file (exif.inf) as well as
//                        fetching EXIF data

function getExifDescription ( $albumDir, $currDir, $image, $formatString )
{

    global $mig_config;

    $aperture   = array ();
    $day        = array ();
    $desc       = array ();
    $flash      = array ();
    $foclen     = array ();
    $iso        = array ();
    $model      = array ();
    $month      = array ();
    $shutter    = array ();
    $time       = array ();
    $year       = array ();

    if (file_exists("$albumDir/$currDir/exif.inf")) {

        $file = fopen("$albumDir/$currDir/exif.inf", 'r');
        $line = fgets($file, 4096);     // get first line
        while (!feof($file)) {

            if (ereg('^File name    : ', $line)) {
                $fname = str_replace('File name    : ', '', $line);
                $fname = chop($fname);

            } elseif (ereg('^Comment      : ', $line)) {
                $comment = str_replace('Comment      : ', '', $line);
                $comment = chop($comment);
                $desc[$fname] = $comment;

            } elseif (ereg('^Camera model : ', $line)) {
                $x = str_replace('Camera model : ', '', $line);
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
                $x = str_replace('Aperture     : ', '', $line);
                // make it fN.N instead of f/N.N
                $x = ereg_replace('/', '', $x);
                $x = chop($x);
                $aperture[$fname] = $x;

            } elseif (ereg('^Focal length : ', $line)) {
                $x = str_replace('Focal length : ', '', $line);
                if (ereg('35mm equiv', $x)) {
                    $x = ereg_replace('^.*alent: ', '', $x);
                    $x = chop($x);
                    $x = ereg_replace('\)$', '', $x);
                }
                $foclen[$fname] = $x;

            } elseif (ereg('^ISO equiv.   : ', $line)) {
                $x = str_replace('ISO equiv.   : ', '', $line);
                $x = chop($x);
                $iso[$fname] = $x;

            } elseif (ereg('^Flash used   : Yes', $line)) {
                $flash[$fname] = $mig_config['lang']['flash_used'];

            } elseif (ereg('^Date/Time    : ', $line)) {
                $x = str_replace('Date/Time    : ', '', $line);
                $x = chop($x);

                // Turn into human readable format and record
                list($w,$x,$y,$z) = parseExifDate($x);

                $year[$fname]     = $w;
                $month[$fname]    = $x;
                $day[$fname]      = $y;
                $time[$fname]     = $z;
            }

            $line = fgets($file, 4096);
        }

        fclose($file);

        $exifData = array ( 'comment'   => $desc[$image],
                            'model'     => $model[$image],
                            'year'      => $year[$image],
                            'month'     => $month[$image],
                            'day'       => $day[$image],
                            'time'      => $time[$image],
                            'iso'       => $iso[$image],
                            'foclen'    => $foclen[$image],
                            'shutter'   => $shutter[$image],
                            'aperture'  => $aperture[$image],
                            'flash'     => $flash[$image]        );

        $retval = formatExifData($formatString, $exifData);

        return $retval;

    } else {
        return '';
    }

}   // -- End of getExifDescription()

