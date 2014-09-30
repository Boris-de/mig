<?php

// getFileType() - Returns TRUE if filetype is supported by Mig.  More specifically, returns
//                 a string filetype.

function getFileType ( $filename )
{
    global $mig_config;
    
    $ext = getFileExtension($filename);
    $ext = strtolower($ext);

    if (in_array($ext, $mig_config['image_extensions'])) {
        return "image";
    } else if (in_array($ext, $mig_config['video_extensions'])) {
        return "video";
    } else if (in_array($ext, $mig_config['audio_extensions'])) {
        return "audio";
    } else {
        return FALSE;
    }

}   // -- End of getFileType()

?>