<?php // $Id$

//
// config.php - Configuration file for Mig
//
// Copyright 2000-2002 Daniel M. Lowe <dan@tangledhelix.com>
//
// http://mig.sourceforge.net/
//
// Lines starting with // are comments.
//
// Please see the files in the docs subdirectory, especially
// docs/text/install.txt (or docs/html/install.html if you prefer HTML).
//


//
// $pageTitle
//     The page title (seen in the title bar of the browser).
//
// Defaults to 'My Photo Album'.
//
// Example:
//     $pageTitle = 'My Photo Album';
//

$pageTitle = 'My Photo Album';


//
// $maintAddr
//     Email address of the person who runs this album.  This is the
//     global setting.  A per-folder maintAddr can be defined using the
//     MaintAddr keyword in a mig.cf file.
//
// Defaults to 'webmaster@mydomain.com'.
//
// Example:
//     $maintAddr = 'webmaster@mydomain.com';
//

$maintAddr = 'webmaster@mydomain.com';


//
// $homeLink
//     "Home" link (optional) - a "home" page to "go back" to from the
//     main page.  Leave blank if you don't want one.
//
// No default.
//
// Example:
//     $homeLink = 'http://mydomain.com/';
//

$homeLink = '';


//
// $homeLabel
//     "Home" link label (optional) - the link label associated with the
//     $homeLink.  Leave value at '' if you don't want one.  If you leave
//     this blank but $homeLink is set, then this will default to the value
//     of $homeLink.
//
// No default.
//
// Example:
//     $homeLabel = 'My Home Page';
//

$homeLabel = '';


//
// $maxFolderColumns
//     Maximum number of columns to use (how many folders across the
//     screen in a single row) in folder lists.
//
// Defaults to 2.
//
// Example:
//     $maxFolderColumns = 2;
//

$maxFolderColumns = 2;


//
// $maxThumbColumns
//     Maximum number of columns to use (how many images across the
//     screen in a single row) in thumbnail lists.
//
// Defaults to 4.
//
// Example:
//     $maxThumbColumns = 4;
//

$maxThumbColumns = 4;


//
// $useThumbSubdir
//     Boolean to turn thumbnail subdirectories on or off.
//     When this is set to TRUE, $markerType and $markerLabel are ignored.
//
// Defaults to TRUE.
//
// Example:
//     $useThumbSubdir = TRUE;
//

$useThumbSubdir = TRUE;


//
// $thumbSubdir
//     What subdirectory to use for thumbnails.  This is ignored if
//     $useThumbSubdir is set to FALSE.
//
// Defaults to 'thumbs'.
//
// Example:
//     $thumbSubdir = 'thumbs';
//

$thumbSubdir = 'thumbs';


//
// $markerType
//     Should I use a prefix or a suffix for thumbnails?  This deals with
//     the old way of doing thumbnail naming (i.e. img_4900_th.jpg
//     instead of thumbs/img_4900.jpg).
//
//     Valid values are 'prefix' and 'suffix'.  Ignored if $useThumbSubdir
//     is TRUE.
//
//     If set to 'suffix', thumbnail will be named as in img_4900_th.jpg.
//     If set to 'prefix', it will be named as in th_img_4900.jpg.
//
//     This option is over-ridden by $useThumbSubdir.  Using thumbnail
//     subdirectories is recommended, so avoid this option and the option
//     $markerLabel if possible.
//
// Defaults to 'suffix'.
//
// Example:
//     $markerType = 'suffix';
//

$markerType = 'suffix';


//
// $markerLabel
//     String to use as marker for thumbnails.  Ignored if $useThumbSubdir
//     is TRUE.
//
//     This deals with the old way of doing thumbnail naming (i.e.
//     img_4900_th.jpg vs. thumbs/img_4900.jpg).
//
//     The "th" part in the example is what is set by $markerLabel.
//     So if you set $markerLabel to "small" then the thumbnail would
//     instead be img_4900_small.jpg.
//
//     This option is over-ridden by $useThumbSubdir.  Using thumbnail
//     subdirectories is recommended, so avoid this option and the option
//     $markerType if possible.
//
// Defaults to 'th'.
//
// Example:
//     $markerLabel = 'th';
//

$markerLabel = 'th';


//
// $suppressImageInfo
//     Boolean to turn image information in thumbnail pages on or off.
//     To suppress information below each thumbnail such as image size,
//     image width/height, set to TRUE.
//
// Defaults to FALSE.
//
// Example:
//     $suppressImageInfo = FALSE;
//

$suppressImageInfo = FALSE;


//
// $suppressAltTags
//     Boolean to use ALT tags or not in thumbnail lists.
//     If you don't want image descriptions in ALT tags in thumbnail
//     lists, set to TRUE.
//
// Defaults to FALSE.
//
// Example:
//     $suppressAltTags = FALSE;
//

$suppressAltTags = FALSE;


//
// $viewCamInfo
//     Boolean to determine whether to show camera settings in EXIF if
//     they're available (i.e. camera model, aperture, shutter, etc).
//     If you want to show camera model, aperture, shutter, focal length,
//     set to TRUE.
//
// Defaults to FALSE.
//
// Example:
//     $viewCamInfo = FALSE;
//

$viewCamInfo = FALSE;


//
// $viewDateInfo
//     Boolean to determine whether to show date from EXIF (if available).
//     If $viewCamInfo is FALSE, then $viewDateInfo is also set to false.
//
//     If you want EXIF info to include the date & time the image was
//     taken, set to TRUE.
//
// Defaults to FALSE.
//
// Example:
//     $viewDateInfo = FALSE;
//

$viewDateInfo = FALSE;


//
// $viewFolderCount
//     Boolean to determine whether or not to count images in each folder
//     and display this, in folder views, next to the folder name.
//
// Defaults to FALSE.
//
// Example:
//     $viewFolderCount = FALSE;
//

$viewFolderCount = FALSE;


//
// $noThumbs
//     Boolean to define whether to suppress thumbnails altogether or not.
//     To not use thumbnails in your galleries at all, set to TRUE.
//
// Defaults to FALSE.
//
// Example:
//     $noThumbs = FALSE;
//

$noThumbs = FALSE;


//
// $thumbExt
//     If you wish you can define a filetype for all thumbnails.  For
//     example, all thumbnails could be GIF files.  Define the extension
//     here (without the leading ".").  Case matters, so don't define
//     'gif' and then upload foo.GIF files.
//
// No default.
//
// Example:
//     $thumbExt = 'gif';
//

$thumbExt = '';


//
// $sortType
//     Sorting type.  Possible values are:
//
//     default          - Alphanumeric sorting
//     bydate-ascend    - By date, ascending (oldest is first)
//     bydate-descend   - By date, descending (newest is first)
//
//     Note that if you define a <Sort> block in a mig.cf file, that will
//     override this setting.
//
// Defaults to 'default'.
//
// Example:
//     $sortType = 'default';
//

$sortType = 'default';


//
// $imagePopup
//     If $imagePopup is set to TRUE, any time a thumbnail is clicked on,
//     a pop-up window will be opened.  See also $imagePopType.
//
// Defaults to FALSE.
//
// Example:
//     $imagePopup = FALSE;
//

$imagePopup = FALSE;


//
// $imagePopType
//     If $imagePopType is set to 'reuse' then the same window will be used
//     for every image.  Otherwise you'll get a new window for every image.
//     Ignored if $imagePopup is set to FALSE.
//
// Defaults to 'reuse'.
//
// Example:
//     $imagePopType = 'reuse';
//

$imagePopType = 'reuse';


//
// $commentFilePerImage
//     If $commentFilePerImage is set to TRUE, then instead of using just
//     the mig.cf file to contain comments, each image file will have a
//     comment  in its own text file (i.e. img_4900.jpg will have a comment
//     file, named img_4900.txt, in the same directory).
//
//     To have "normal" processing (all comments in mig.cf), set to FALSE.
//
// Defaults to FALSE.
//
// Example:
//     $commentFilePerImage = FALSE;
//

$commentFilePerImage = FALSE;


//
// $mig_language
//     What language to use.
//
//       en      English
//       fr      French
//       de      German
//       no      Norwegian
//       br      Portugese
//       fi      Finnish
//       ro      Romanian
//       ru      Russian Windows-1251
//       koi8r   Russian KOI8-R
//       tr      Turkish
//       se      Swedish
//       dk      Danish
//       it      Italian
//       es      Spanish
//       sk      Slovak
//       nl      Dutch
//       pl      Polish
//
//     If you want to translate Mig into another language, please contact
//     me at dan@tangledhelix.com.
//
//     (Note: this variable used to be called $language, but that is
//     deprecated and it should be used as $mig_language as of
//     version 1.2.2)
//
// Defaults to 'en'.
//
// Example:
//     $mig_language = 'en';
//

$mig_language = 'en';


//
// $jumpMap
//     You can add things to your "jump" map - see the "jump" document.
//
// Default is no jump map.
//
// Example:
//     $jumpMap['example'] = 'currDir=./Mig_Example_Gallery';
//


// -----------------------------------------------------------------
//                     PHP-Nuke related items
// -----------------------------------------------------------------
//
// $phpNukeCompatible
//     PHP-Nuke compatibility mode (www.phpnuke.org)
//     For more information, see the "phpnuke" document.
//
//     This was last tested with PHPNuke 4.2 - I have no idea if this
//     works with anything newer than that!
//
//    Set this to TRUE only if you're using PHP-Nuke.
//
// Defaults to FALSE.
//
// Examples:
//     $phpNukeCompatible = TRUE;
//     $phpNukeCompatible = FALSE;  (default)
//

$phpNukeCompatible = FALSE;

//
// $phpNukeRoot
//     Set this to the full path to the root of your phpNuke install.
//     (Ignored if $phpNukeCompatible is set to FALSE).
//
// No default.
//
// Example:
//     $phpNukeRoot = '/usr/apache/htdocs';
//

$phpNukeRoot = '';


// -----------------------------------------------------------------
//                        PASSWORD PROTECTION
// -----------------------------------------------------------------
//
// Password protection configuration
//
// Format:
//     $protect[Directory][user] = password;
//
// Example:
//     $protect['./Example_Gallery']['joe'] = 'IBDXWbkBirMfU';
//
// Passwords are in crypt() format.  See the "passwords" document.
// Need more than one directory and/or user?  that's fine, just add more
// than one line.
//
// THIS FEATURE IS NOT REALLY SECURE.  Please don't rely on it for your
// security needs unless they're fairly superficial.
//


?>
