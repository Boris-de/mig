<?php // $Revision$

//
// config.php - Configuration file for Mig
//
// Copy this file to config.php before customizing it - the installer
// will over-write config.php.default, but will not over-write config.php.
//
// Copyright 2000-2002 Daniel M. Lowe <dan@tangledhelix.com>
//
// http://mig.sourceforge.net/
//
// Lines starting with // are comments.
//
// Please see the files in the docs subdirectory, especially docs/INSTALL
// (or docs/html/Install.html if you prefer HTML).
//


// Page title (seen in the titlebar of the browser)
//
$pageTitle = 'Photo Album';


// Email address of the person who runs this album (correlates to
// template tag "%%maintAddr%%")
//
// This is the global setting.  You can define a different maintAddr
// in a given folder using the MaintAddr keyword in the mig.cf file.
//
// Example:
//     $maintAddr = 'webmaster@mydomain.com';
//
$maintAddr = 'webmaster@mydomain.com';


// "Home" link (optional) -- a "home" page to "go back" to from the
// main page.  Leave value at '' if you don't want one.
//
// Example:
//     $homeLink = 'http://mydomain.com/';
//
$homeLink = '';


// "Home" link label (optional) -- the link label associated with the
// $homeLink.  Leave value at '' if you don't want one.  If you leave
// this blank but $homeLink is set, then this will default to the value
// of $homeLink.
//
// Example:
//     $homeLabel = 'My Home Page';
//
$homeLabel = '';


// Maximum number of columns to use (how many folders across the
// screen in a single row) in folder lists
//
$maxFolderColumns = 1;


// Maximum number of columns to use (how many images across the
// screen in a single row) in thumbnail lists
//
$maxThumbColumns = 4;


// Boolean to turn thumbnail subdirectories on or off.
// Defaults to TRUE.
//
// To use a subdirectory for thumbnails, set this to TRUE.
// When this is set to true, $markerType and $markerLabel are ignored.
//
$useThumbSubdir = TRUE;


// What subdirectory to use for thumbnails.  This is ignored if
// $useThumbSubdir is set to FALSE.
//
$thumbSubdir = 'thumbs';


// Should I use a prefix or a suffix for thumbnails?
// (see docs/INSTALL and docs/Utilities.txt)
//
// Valid values are 'prefix' and 'suffix'.  Ignored if $useThumbSubdir
// is TRUE.
//
$markerType = 'suffix';


// String to use as marker for thumbnails (see docs/INSTALL and
// docs/Utilities.txt).  Ignored if $useThumbSubdir is TRUE.
//
$markerLabel = 'th';


// Boolean to turn image information in thumbnail pages on or off.
// Defaults to FALSE.
//
// To suppress information below each thumbnail such as image size,
// image width/height, set to TRUE.
//
$suppressImageInfo = FALSE;


// Boolean to use ALT tags or not in thumbnail lists.
// Defaults to FALSE.
//
// If you don't want image descriptions in ALT tags in thumbnail
// lists, set to TRUE.
//
$suppressAltTags = FALSE;


// Boolean to determine whether to show camera settings in EXIF if
// they're available (i.e. camera model, aperture, shutter, etc).
// Defaults to FALSE.
//
// If you want to show camera model, aperture, shutter, focal length,
// set to TRUE.
//
$viewCamInfo = FALSE;


// Boolean to determine whether to show date from EXIF (if available).
// Defaults to FALSE.  If $viewCamInfo is FALSE, then $viewDateInfo
// is also set to false
//
// If you want EXIF info to include the date & time the image was
// taken, set to TRUE.
//
$viewDateInfo = FALSE;


// Boolean to determine whether or not to count images in each folder
// and display this, in folder views, next to the folder name.
// Defaults to FALSE.
//
$viewFolderCount = FALSE;


// Boolean to define whether to suppress thumbnails altogether or not.
// Defaults to FALSE.
//
// To not use thumbnails in your galleries at all, set to TRUE.
//
$noThumbs = FALSE;


// If you wish you can define a filetype for all thumbnails.  For
// example, all thumbnails could be GIF files.  Define the extension
// here (without the leading ".").  Case matters, so don't define
// 'gif' and then upload foo.GIF files.
//
// Example:
//     $thumbExt = 'gif';
//
$thumbExt = '';


// Sorting type (defaults to 'default')
//
// default          - Alphanumeric sorting
// bydate-ascend    - By date, ascending (oldest is first)
// bydate-descend   - By date, descending (newest is first)
//
// Note that if you define a <Sort> block in a mig.cf file, that will
// override this setting.
//
$sortType = 'default';


// Image Pop-up Windows
//
// If $imagePopup is set to TRUE, any time a thumbnail is clicked on,
// a pop-up window will be opened.  Defaults to FALSE.
//
$imagePopup = FALSE;


// If $imagePopType is set to 'reuse' then the same window will be used
// for every image.  Otherwise you'll get a new window for every image.
// Defaults to 'reuse'.
//
$imagePopType = 'reuse';


// if $commentFilePerImage is set to TRUE, then instead of using just
// the mig.cf file to contain comments, each image file will have a comment
// in its own text file (i.e. img_4900.jpg will have a comment file, named
// img_4900.txt, in the same directory).
//
// To have "normal" processing (all comments in mig.cf), set to FALSE.
// Defaults to FALSE.
//
$commentFilePerImage = FALSE;


// What language to use  (default is 'en' - English)
//
// Possible languages:
//   en     English
//   fr     French
//   de     German
//   no     Norwegian
//   br     Portugese
//   fi     Finnish
//   ro     Romanian
//   ru     Russian Windows-1251
//   koi8r  Russian KOI8-R
//   tr     Turkish
//   se     Swedish
//   da     Danish
//   it     Italian
//   es     Spanish
//   sk     Slovak
//   nl     Dutch
//   pl     Polish
//
// If you want to translate Mig into another language, please contact
// dan@tangledhelix.com.
//
// (Note: this variable used to be called $language, but that is deprecated
// and it should be used as $mig_language as of version 1.2.2)
//
$mig_language = 'en';


// You can add things to your "jump" map - see docs/Jump.txt
//
//$jumpMap['example'] = 'currDir=./Example_Gallery';


// -----------------------------------------------------------------
//
// phpNuke compatibility mode (www.phpnuke.org)
// For more information, see docs/phpNuke.txt
//
// This was last tested with PHPNuke 4.2 - I have no idea if this
// works with anything newer than that!
//
//     Set this to either TRUE or FALSE (without quotes)
//     Examples:
//         $phpNukeCompatible = TRUE;
//         $phpNukeCompatible = FALSE;
//
$phpNukeCompatible = FALSE;

//     Set this to the full path to the root of your phpNuke install.
//     (Ignored if $phpNukeCompatible is set to FALSE).
//
//     Example:
//         $phpNukeRoot = '/usr/apache/htdocs';
//
$phpNukeRoot = '';


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
// Passwords are in crypt() format.  See docs/Passwords.txt for more.
// Need more than one directory and/or user?  that's fine, just add more
// than one line.
//
// THIS FEATURE IS NOT REALLY SECURE.  Please don't rely on it for your
// security needs unless they're fairly superficial.
//


// -----------------------------------------------------------------
//
// For people whose Apache or PHP doesn't support virtual() - you should
// only bother with this if you get errors related to virtual().
//
// If you do have errors like that, you can try setting this to FALSE
// and see if they go away.
//
$useVirtual = TRUE;

?>
