<?php

// getExifDescription() - Fetches a comment if available from the Exif comments file (exif.inf)
//                        as well as fetching EXIF data.

function getExifDescription ( $currDir, $formatString )
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

    if (file_exists($mig_config['albumdir']."/$currDir/exif.inf")) {

        $file = fopen($mig_config['albumdir']."/$currDir/exif.inf", 'r');
        $line = fgets($file, 4096);     // get first line
        while (!feof($file)) {

            if (strpos($line, 'File name    : ') === 0) {
                $fname = str_replace('File name    : ', '', $line);
                $fname = chop($fname);

            } elseif (strpos($line, 'Comment      : ') === 0) {
                $comment = str_replace('Comment      : ', '', $line);
                $comment = chop($comment);
                $desc[$fname] = $comment;

            } elseif (strpos($line, 'Camera model : ') === 0) {
                $x = str_replace('Camera model : ', '', $line);
                $x = chop($x);
                $model[$fname] = $x;

            // This one apparently sometimes has a space after
            // the colon, sometimes not.  Try to work either way.
            } elseif (preg_match('#^Exposure time: ?#', $line)) {
                $x = preg_replace('#^Exposure time: ?#', '', $line);
                if (preg_match('#\(#', $x)) {
                    $x = preg_replace('#^.*\(#', '', $x);
                    $x = preg_replace('#).*$#', '', $x);
                }
                $x = chop($x);
                $shutter[$fname] = $x;

            } elseif (strpos($line, 'Aperture     : ') === 0) {
                $x = str_replace('Aperture     : ', '', $line);
                // make it fN.N instead of f/N.N
                $x = preg_replace('#/#', '', $x);
                $x = chop($x);
                $aperture[$fname] = $x;

            } elseif (strpos($line, 'Focal length : ') === 0) {
                $x = str_replace('Focal length : ', '', $line);
                if (stripos($x, '35mm equiv') !== FALSE) {
                    $x = preg_replace('#^.*alent: #', '', $x);
                    $x = chop($x);
                    $x = preg_replace('#)$#', '', $x);
                }
                $foclen[$fname] = $x;

            } elseif (strpos($line, 'ISO equiv.   : ') === 0) {
                $x = str_replace('ISO equiv.   : ', '', $line);
                $x = chop($x);
                $iso[$fname] = $x;

            } elseif (strpos($line, 'Flash used   : Yes') === 0) {
                $flash[$fname] = $mig_config['lang']['flash_used'];

            } elseif (strpos($line, '^Date/Time    : ') === 0) {
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
        
        $image = $mig_config['image'];

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

?>