<?php

// getExifDescription() - Fetches a comment if available from the Exif comments file (exif.inf)
//                        as well as fetching EXIF data.

function _exif_default_string($str) {
    return $str !== null && $str !== FALSE ? $str : '';
}

function getExifDescription ( $unsafe_currDir, $formatString )
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
    $knownFiles = array ();

    $localExifFilename = $mig_config['albumdir'] . "/$unsafe_currDir/exif.inf";
    if (file_exists($localExifFilename)) {
        $fname = NULL;
        $file = fopen($localExifFilename, 'r');
        if ($file === FALSE) {
            return '';
        }
        while (!feof($file)) {
            $line = fgets($file, 4096);
            if ($line === FALSE) {
                continue;
            }
            if (strpos($line, 'File name    : ') === 0) {
                $fname = str_replace('File name    : ', '', $line);
                $fname = chop($fname);

                $knownFiles[$fname] = TRUE;
                $desc[$fname] = '';
                $model[$fname] = '';
                $year[$fname] = '';
                $month[$fname] = '';
                $day[$fname] = '';
                $time[$fname] = '';
                $iso[$fname] = '';
                $foclen[$fname] = '';
                $shutter[$fname] = '';
                $aperture[$fname] = '';
                $flash[$fname] = '';
            } elseif ($fname === NULL) {
                continue; // no need to parse a line that we cannot store without a filename
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
                if ($x != null && preg_match('#\(#', $x)) {
                    $x = _exif_default_string(preg_replace('#^.*\(#', '', $x));
                    $x = _exif_default_string(preg_replace('#\).*$#', '', $x));
                }
                $x = chop(_exif_default_string($x));
                $shutter[$fname] = $x;

            } elseif (strpos($line, 'Aperture     : ') === 0) {
                $x = str_replace('Aperture     : ', '', $line);
                // make it fN.N instead of f/N.N
                $x = _exif_default_string(preg_replace('#/#', '', $x));
                $x = chop($x);
                $aperture[$fname] = $x;

            } elseif (strpos($line, 'Focal length : ') === 0) {
                $x = chop(str_replace('Focal length : ', '', $line));
                if (stripos($x, '35mm equiv') !== FALSE) {
                    $x = _exif_default_string(preg_replace('#^.*alent: #', '', $x));
                    $x = chop($x);
                    $x = _exif_default_string(preg_replace('#\)$#', '', $x));
                }
                $foclen[$fname] = $x;

            } elseif (strpos($line, 'ISO equiv.   : ') === 0) {
                $x = str_replace('ISO equiv.   : ', '', $line);
                $x = chop($x);
                $iso[$fname] = $x;

            } elseif (strpos($line, 'Flash used   : Yes') === 0) {
                $flash[$fname] = $mig_config['lang']['flash_used'];

            } elseif (strpos($line, 'Date/Time    : ') === 0) {
                $x = str_replace('Date/Time    : ', '', $line);
                $x = chop($x);

                // Turn into human readable format and record
                list($w,$x,$y,$z) = parseExifDate($x);

                $year[$fname]     = $w;
                $month[$fname]    = $x;
                $day[$fname]      = $y;
                $time[$fname]     = $z;
            }
        }

        fclose($file);

        $unsafe_image = $mig_config['unsafe_image'];

        if (!isset($knownFiles[$unsafe_image])) {
            return '';
        }
        $exifData = array ( 'comment'   => $desc[$unsafe_image],
                            'model'     => $model[$unsafe_image],
                            'year'      => $year[$unsafe_image],
                            'month'     => $month[$unsafe_image],
                            'day'       => $day[$unsafe_image],
                            'time'      => $time[$unsafe_image],
                            'iso'       => $iso[$unsafe_image],
                            'foclen'    => $foclen[$unsafe_image],
                            'shutter'   => $shutter[$unsafe_image],
                            'aperture'  => $aperture[$unsafe_image],
                            'flash'     => $flash[$unsafe_image]        );

        return formatExifData($formatString, $exifData);

    } else {
        return '';
    }

}   // -- End of getExifDescription()

?>
