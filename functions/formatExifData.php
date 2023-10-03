<?php

// formatExifData() - Formats EXIF data according to $exifFormatString.

function formatExifData ( $formatString, $exifData )
{
    // %a   Aperture
    // %c   Comment
    // %f   Flash used?
    // %i   ISO rating
    // %l   Focal length
    // %m   Camera model
    // %s   Shutter speed

    // %Y   Year
    // %M   Month
    // %D   Day
    // %T   Time

    $table = array ( 'c' => $exifData['comment'],
                     'a' => $exifData['aperture'],
                     'f' => $exifData['flash'],
                     'i' => $exifData['iso'],
                     'l' => $exifData['foclen'],
                     'm' => $exifData['model'],
                     's' => $exifData['shutter'],
                     'Y' => $exifData['year'],
                     'M' => $exifData['month'],
                     'D' => $exifData['day'],
                     'T' => $exifData['time']           );
                     
    // separate elements of format string
    $matches = explode('|', $formatString);

    $newstr = '';
    foreach ($matches as $val) {

        // Keep on going until every %X atom has been examined and
        // expanded.

        while (preg_match('#%([a-zA-Z])#', $val, $lettermatch)) {

            // which letter matched?
            $letter = $lettermatch[1];

            // If this can be expanded, do so.  If it can be,
            // set $changeflag to TRUE so we know to include this
            // block instead of dumping it.
            if (isset($table[$letter])) {
                $newtext = $table[$letter];
            } else {
                $newtext = '';
            }

            // Do interpolation
            $val = str_replace("%$letter", $newtext, $val);
        }

        $newstr .= $val;
    }
    
    return $newstr;

}   // -- End of formatExifData()

?>
