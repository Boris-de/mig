<?php

// formatExifData() - Formats EXIF data according to $exifFormatString.

function formatExifData ( $formatString, $exifData )
{
    global $mig_config;
    
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
    
    while (list($key,$val) = each($matches)) {
    
        // $changeflag is used to tell us if we should bother
        // printing this block at all.  If none of the format
        // characters in this block can be expanded, we never set
        // $changeflag to TRUE.  If it's not TRUE at the end of this
        // while(), the block is just dumped.
        $changeflag = FALSE;

        // Keep on going until every %X atom has been examined and
        // expanded.

        while (ereg('%([a-zA-Z])', $val, $lettermatch)) {

            // which letter matched?
            $letter = $lettermatch[1];

            // If this can be expanded, do so.  If it can be,
            // set $changeflag to TRUE so we know to include this
            // block instead of dumping it.
            if ($table[$letter]) {
                $newtext = $table[$letter];
                $changeflag = TRUE;
            }

            // Do interpolation
            $val = str_replace("%$letter", $newtext, $val);
        }

        // Only if $changeflag is TRUE do we bother tacking this
        // onto the final product.
        if ($changeflag) {
            $newstr .= $val;
        }
            
    }
    
    return $newstr;

}   // -- End of formatExifData()

?>