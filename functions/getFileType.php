<?php

// getFileType() - Returns TRUE if filetype is supported by Mig.  More specifically, returns
//                 a string filetype.

function getFileType ( $filename )
{
    global $mig_config;
    
    $ext = getFileExtension($filename);
    $ext = strtolower($ext);

    switch ($ext) {

        case 'jpg':
        case 'gif':
        case 'png':
        case 'jpeg': case 'jpe':// Alternate JPEG

            return 'image';
            break;

        case 'mov':             // Apple Quicktime
        case 'avi':             // AVI-container
        case 'mpg': case 'mpeg':// MPEG video
        case 'wmv':             // Windows Media video
        case 'mp4':             // MPEG-4 video
        case 'swf':             // Shockwave Flash
        case 'flv':             // Flash Video
        case 'rm':              // Realvideo
        case 'divx':            // DivX

            return 'video';
            break;

        case 'mp3':             // MPEG-3 audio
        case 'wav':             // WAV audio
        case 'ra':              // Realaudio
        case 'ram':             // Realaudio
        case 'wma':             // Microsoft Media Audio
        case 'ogg':             // ogg-container (flac, vorbis)
        case 'flac':            // Free Lossless Audio Codec
        case 'aac':             // Advanced Audio Coding
        case 'mpc': case 'mp+': // Musepack

            return 'audio';
            break;

        default:
            return FALSE;       // No valid match - failure.
            break;
    }

}   // -- End of getFileType()

?>