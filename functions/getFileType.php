<?php

// getFileType() - Returns TRUE if filetype is supported by Mig.
//                 More specifically, returns a string filetype.

function getFileType ( $filename )
{
    $ext = getFileExtension($filename);
    $ext = strtolower($ext);

    switch ($ext) {

        case "jpg":
        case "gif":
        case "png":
        case "jpeg":            // Alternate JPEG
        case "jpe":             // Alternate JPEG

            return "image";
            break;

        case "mov":             // Apple Quicktime
        case "avi":             // Microsoft AVI
        case "mpg":             // MPEG video
        case "mpeg":            // Alternate MPEG video
        case "wmv":             // Windows Media Video
        case "mp4":             // MPEG-4 video

            return "video";
            break;

        case "mp3":             // MPEG-3 audio
        case "wav":             // Microsoft WAV audio
        case "ra":              // Realaudio
        case "ram":             // Realaudio

            return "audio";
            break;

        default:
            return FALSE;       // No valid match - failure.
            break;
    }

}   // -- End of getFileType()

?>
