<?php

// Defaults - these values can be over-ridden using config.php
//

$commentFilePerImage        = FALSE;
$commentFileShortComments   = FALSE;
$distURL                    = 'http://mig.sourceforge.net/';
$exifFormatString           = '|%c|';
$fileInfoFormatString['image'] = "%n<br>(%i, %s)";
$fileInfoFormatString['audio'] = "%n<br>(%s)";
$fileInfoFormatString['video'] = "%n<br>(%s)";
$folderNameLength           = 15;
$folderSortType             = 'default';
$ignoreDotDirectories       = FALSE;
$imagePopLocationBar        = FALSE;
$imagePopMaxHeight          = 480;
$imagePopMaxWidth           = 640;
$imagePopMenuBar            = FALSE;
$imagePopToolBar            = FALSE;
$imagePopType               = 'reuse';
$imagePopup                 = FALSE;
$largeLinkFromMedium        = TRUE;
$largeLinkUseBorders        = FALSE;
$largeSubdir                = 'large';
$maintAddr                  = 'webmaster@mydomain.com';
$markerLabel                = 'th';
$markerType                 = 'suffix';
$maxFolderColumns           = 2;
$maxThumbColumns            = 4;
$maxThumbRows               = 5;
$mig_GeeklogCompatible      = FALSE;
$mig_GeeklogRBlockForFolder = 1;
$mig_GeeklogRBlockForImage  = 0;
$mig_GeeklogRoot            = '';
$mig_language               = 'en';
$mig_xoopsCompatible        = FALSE;
$mig_xoopsRBlockForFolder   = 1;
$mig_xoopsRBlockForImage    = 0;
$mig_xoopsRoot              = '../..';
$nextFormatString           = '%l';
$noThumbs                   = FALSE;
$omitImageName              = FALSE;
$pageTitle                  = 'My Photo Album';
$phpNukeCompatible          = FALSE;
$phpNukeRoot                = '';
$phpWebThingsCompatible     = FALSE;
$phpWebThingsRoot           = '';
$prevFormatString           = '%l';
$randomFolderThumbs         = FALSE;
$showShortOnThumbPage       = FALSE;
$sortType                   = 'default';
$suppressAltTags            = FALSE;
$suppressImageInfo          = FALSE;
$thumbSubdir                = 'thumbs';
$useLargeImages             = FALSE;
$useRealRandThumbs          = TRUE;
$useThumbSubdir             = TRUE;
$viewFolderCount            = FALSE;
$imageFilenameRegexpr       = '=^[^<>/]*$=';
$currDirNameRegexpr         = '=^([^<>]|\.\.)*$=';
$httpContentType            = 'text/html; charset=us-ascii';
$onlySendIfXhtmlIsAccepted  = FALSE;
$music_icon                 = 'music.png';
$movie_icon                 = 'movie.png';
$folder_icon                = 'folder.png';
$nothumb_icon               = 'no_thumb.png';
$showTotalImagesString      = true;
$error_reporting            = error_reporting() & ~E_NOTICE;
$image_extensions           = array('jpg', 'jpeg', 'jpe', 'gif', 'png');
$video_extensions           = array(
        'mov',             // Apple Quicktime
        'avi',             // AVI-container
        'mpg', 'mpeg',     // MPEG video
        'wmv',             // Windows Media video
        'mp4',             // MPEG-4 video
        'swf',             // Shockwave Flash
        'flv',             // Flash Video
        'rm',              // Realvideo
        'divx',            // DivX
    );
$audio_extensions           = array(
        'mp3',             // MPEG-3 audio
        'wav',             // WAV audio
        'ra', 'ram',       // Realaudio
        'wma',             // Microsoft Media Audio
        'ogg',             // ogg-container (flac, vorbis)
        'flac',            // Free Lossless Audio Codec
        'aac',             // Advanced Audio Coding
        'mpc', 'mp+',      // Musepack
    );

?>
