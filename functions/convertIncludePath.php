<?php

// convertIncludePath() - Converts the path used by include() if needed.
//                        (Not normally needed, but some installs of PHP
//                        demand this).

function convertIncludePath ( $flag, $path='', $regex, $new )
{
    if ($flag) {
        $path = ereg_replace($regex, $new, $path);
    }

    return $path;

}   // -- End of convertIncludePath()

?>
