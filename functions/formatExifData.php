

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

    $table = array ( "c" => $exifData["comment"],
                     "a" => $exifData["aperture"],
                     "f" => $exifData["flash"],
                     "i" => $exifData["iso"],
                     "l" => $exifData["foclen"],
                     "m" => $exifData["model"],
                     "s" => $exifData["shutter"],
                     "Y" => $exifData["year"],
                     "M" => $exifData["month"],
                     "D" => $exifData["day"],
                     "T" => $exifData["time"]           );

    // get rid of trailing | character if there is one
    $formatString = ereg_replace("\|$", "", $formatString);

    // Nibble away at format string until it is empty
    while ($formatString) {

        // Try to match a block (a block is a pipe followed by a format
        // atom (such as %c) surrounded by optional text)

        if (ereg("^\|[^|]*%[a-zA-Z][^|]*", $formatString, $matches)) {

            $x = $matches[0];               // entire match (this is the
                                            // block pattern, which we
                                            // can work on as a whole now)

            $x = str_replace("|","", $x);   // get rid of leading | char

            // $changeflag is used to tell us if we should bother
            // printing this block at all.  If none of the format
            // characters in this block can be expanded, we never set
            // $changeflag to TRUE.  If it's not TRUE at the end of this
            // while(), the block is just dumped.
            $changeflag = FALSE;

            // Keep on going until every %X atom has been examined and
            // expanded.

            while (ereg("%([a-zA-Z])", $x, $lettermatch)) {

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
                $x = str_replace("%$letter", $newtext, $x);
            }

            // Only if $changeflag is TRUE do we bother tacking this
            // onto the final product.
            if ($changeflag) {
                $newstr .= $x;
            }

            // shrink format string by one block.
            $formatString = ereg_replace("^\|[^|]+", "", $formatString);
        }
    }

    return $newstr;

}   // -- End of formatExifData()

