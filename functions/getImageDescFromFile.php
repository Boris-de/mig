<?php

// getImageDescFromFile() - Fetches an image description from a per-image comment file
//                          (only used if $commentFilePerImage is TRUE).

function getImageDescFromFile ( $unsafe_currDir, $unsafe_image )
{
    global $mig_config;

    $imageDesc = '';
    $unsafe_fname = getFileName($unsafe_image);
    $unsafe_fname = rawurldecode($unsafe_fname);

    $short_flag = $mig_config['commentfileshortcomments'];

    $localDescFileName = $mig_config['albumdir'] . "/$unsafe_currDir/$unsafe_fname.txt";

    if (file_exists($localDescFileName)) {

        $file = fopen($localDescFileName, 'r');
        if ($file === FALSE) {
            return FALSE;
        }
        $line = fgets($file, 4096);         // get first line

        // This double-check exists so that files ending without
        // a proper newline character are not truncated.
        // This says "while (not EOF) and ($line is not empty)"...
        $short_desc = '';
        while ($line !== FALSE || ! feof($file)) {
            $line = $line !== FALSE ? trim($line) : '';

            // If the "short comments" flag is set, and there is
            // not yet a short description, set one.  This means this
            // must be the first line of the file.
            if ($short_flag && ! $short_desc) {
                $short_desc = $line;
            } else {
                // Otherwise just build on the main description (2nd
                // and further lines, in $firstLine mode, otherwise
                // all lines go here)
                $imageDesc .= "$line ";
            }
            $line = fgets($file, 4096); // get next line
        }

        fclose($file);

        // If there's not a long description, use the short_desc, if we
        // are in "short" mode.
        if ($short_flag && ! $imageDesc) {
            $imageDesc = $short_desc;
        }

        // If we're not in short mode, make sure there's an alt tag
        // of the comment if one exists
        if ($imageDesc && ! $short_desc) {
            $short_desc = $imageDesc;
        }

    } else {
        // File doesn't exist? Okay, return false.
        return FALSE;
    }

    return array ( trim($short_desc), trim($imageDesc) );

}   // -- End of getImageDescFromFile()

?>
