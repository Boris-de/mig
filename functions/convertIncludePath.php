<?php

// convertIncludePath() - Converts the path used by include() if needed.
//                        (Not normally needed, but some installations demand this).

function convertIncludePath ( $flag, $path, $regex, $new )
{
    global $mig_config;
    
    if ($flag) {
        $path = preg_replace($regex, $new, $path);
    }

    return $path;

}   // -- End of convertIncludePath()

?>