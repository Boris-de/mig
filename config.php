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
//     screen in a single row) in thumbnail lists.  (see also
//     $maxThumbRows)
//
// Defaults to 4.
//
// Example:
//     $maxThumbColumns = 4;
//

$maxThumbColumns = 4;


//
// $maxThumbRows
//      Maximum number of rows to use (how many rows of thumbnails on the
//      screen on a single page) in thumbnail views.  The total number of
//      images on one "page" is ($maxThumbColumns * $maxThumbRows),
//      so by default, 20 images per page.  Mig will turn a given
//      gallery into a set of pages if there are more than 20 images
//      in a gallery.
//
//      If you don't want to use "pages" at all, set this to some
//      very large value like 1000.  If you pages of more than 20,
//      increase the value of $maxThumbRows and/or $maxThumbColumns
//      as appropriate.
//
// Defaults to 5.
//
// Example:
//      $maxThumbRows = 5;
//

$maxThumbRows = 5;


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
// $randomFolderThumbs
//      If TRUE, instead of the generic folder icon, a represenative
//      thumbnail from the folder is shown.  If Mig is unable to find a
//      thumbnail to use, it will use the generic folder icon instead.
//      Unless there are folders whose subfolders and there subfolders
//      and so on have no thumbnails, the generic folder icon should
//      never be used.
//
// Defaults to FALSE.
//
// Example:
//      $randomFolderThumbs = FALSE;
//

$randomFolderThumbs = FALSE;


//
// $folderNameLength
//      If $randomFolderThumbs is TRUE, folder names will be truncated
//      if they are longer than $folderNameLength characters.  This is
//      done to keep the tables formatted nicely.  This option is ignored
//      if $randomFolderThumbs is FALSE.
//
// Defaults to 15.
//
// Example:
//      $folderNameLength = 15;
//

$folderNameLength = 15;


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
// $showShortOnThumbPage
//      If set to TRUE, shows any short comment on the thumbnail page.
//      Note that enabling this can lead to /very/ ugly formatting
//      currently due to the way tables are laid out, if you forget
//      to set a short comment on an image, but a long one exists.
//
// Defaults to FALSE.
//
// Example:
//      $showShortOnThumbPage = FALSE
//

$showShortOnThumbPage = FALSE;


//
// $viewFolderCount
//     Boolean to determine whether or not to show number of images and
//     subdirectories in a given folder when in thumbnail views.
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
//     a pop-up window will be opened.  See also $imagePopType,
//     $imagePopLocationBar, $imagePopMenuBar, and $imagePopToolBar.
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
// $imagePopLocationBar
//      If TRUE, a location bar will be visible in pop-up windows.
//      A location bar is the box where one can type a URL into a
//      web browser.
//
// Defaults to FALSE.
//
// Example:
//      $imagePopLocationBar = FALSE;
//

$imagePopLocationBar = FALSE;


//
// $imagePopMenuBar
//      If TRUE, a menu bar will be visible in pop-up windows.
//      Note that this has no real effect on a Mac, since Macs
//      use shared menubars.
//
// Defaults to FALSE.
//
// Example:
//      $imagePopMenuBar = FALSE;
//

$imagePopMenuBar = FALSE;


//
// $imagePopToolBar
//      If TRUE, a toolbar will be visible in pop-up windows.
//      This is sometimes called the navigation bar (where the back,
//      reload buttons are found, and so forth).
//
// Defaults to FALSE.
//
// Example:
//      $imagePopToolBar = FALSE;
//

$imagePopToolBar = FALSE;


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
// $commentFileShortComments
//      If $commentFileShortComments is set to TRUE, and $commentFilePerImage
//      is being used, the first line of a comment file will be used as a
//      short comment (for ALT tags).  The remaining lines will be used as
//      the normal ("long") comment.  If a comment file contains only one
//      line, this line is used for both comments.
//
//      To disable short comments and use the entire file for both, set
//      $commentFileShortComments to FALSE.  This option is ignored if
//      $commentFilePerImage is FALSE.
//
// Defaults to FALSE.
//
// Example:
//      $commentFileShortComments = FALSE;
//

$commentFileShortComments = FALSE;


//
// $exifFormatString
//     Defines the display format for EXIF data blocks.  Sections are
//     separated by | characters.  For example:
//     
//         '|%c<hr>|%M %D %Y, %T - |%m<br>|%l |%s |%a|'
//
//     Each block is contained between two | characters.  If any one
//     item inside the block can be expanded, the block will be printed.
//     If not, it will be ignored.  (This way if you have some images
//     with comments, some without, it won't always print that <HR>
//     for example... it will ignore it... same with the hyphen after
//     the timestamp, or the comma in between the year and the time).
//
//     Valid items are:
//         %a   Aperture
//         %c   Embedded comment
//         %f   Flash used
//         %i   ISO equivalent
//         %l   Focal length
//         %m   Camera model
//         %s   Shutter speed
//         %Y   Year
//         %M   Month (alphabetic, i.e. "Mar")
//         %D   Day
//         %T   Time (i.e. "12:54PM")
//
//     (Dates and times are original shooting date and time, not the
//     timestamp of the file.)
//
//     So in the above example, for instance, if there is an embedded
//     comment, that comment would be printed followed by "<HR>".  But
//     if no comment is present for the image, nothing will be printed.
//     If the month, day, year, or time (or all of them) can be expanded,
//     that block will print.  Otherwise it will be ignored.  And so on.
//
//     The default (shown below) might expand to look something like this
//     on your screen:
//
//                  This is a comment
//         --------------------------------------
//          Jan 04 2002, 12:54PM - Canon EOS D30
//           ISO 100 85mm 1/45 f5.6 (flash used)
//
// Defaults to '|%c|' - which is just embedded comments.
//
// Examples:
//
//  Just the date and time:
//     $exifFormatString = '|%M %D %Y, %T|';
//
//  What used to be called $viewCamInfo would look like this:
//     $exifFormatString = '|%c<hr>|%m<br>|ISO %i |%l |%s |%a |(%f)|';
//
//  What used to be $viewCamInfo and $viewDateInfo together is:
//     '|%c<hr>|%M %D %Y, %T - |%m<br>|ISO %i |%l |%s |%a |(%f)|';
//

$exifFormatString = '|%c|';


//
// $mig_language
//     What language to use.
//
//     Currently available languages:
//       en      English
//       fr      French
//       de      German
//       no      Norwegian
//       br      Portugese
//       fi      Finnish
//       ro      Romanian
//       ru      Russian (Windows-1251)
//       koi8r   Russian (KOI8-R)
//       tr      Turkish
//       se      Swedish
//       dk      Danish
//       it      Italian
//       es      Spanish
//       sk      Slovak
//       nl      Dutch
//       pl      Polish
//       ee      Estonian
//       jp      Japanese (ISO-2022-JP)
//       pliso   Polish ISO-8859-2
//
//     If you want to translate Mig into another language, please contact
//     me via email (dan@tangledhelix.com).
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
// PHPNUKE / POSTNUKE / PHPWEBTHINGS COMPATIBILITY
// -----------------------------------------------------------------
//
// If you are using PHP-Nuke (www.phpnuke.org), PostNuke (www.postnuke.com)
// phpWebThings (www.phpdbform.com) or phpWebSite (phpwebsite.appstate.edu),
// you can tell Mig to try to cooperate with your content system.  See the
// "phpnuke" document for more information.
//
// $phpNukeCompatible
//      Set to TRUE if you're using PHP-Nuke, PostNuke or phpWebSite.
// $phpWebThingsCompatible
//      Set to TRUE if you're using phpWebThings.
//
//      Obviously you only want to set ONE of these two to TRUE.
//
// Both default to FALSE.
// 
// Example:
//      $phpNukeCompatible = FALSE;
//      $phpWebThingsCompatible = FALSE;
//

$phpNukeCompatible = FALSE;
$phpWebThingsCompatible = FALSE;

//
// $phpNukeRoot
//      Set to the root directory of your PHP-Nuke, PostNuke or
//      phpWebSite system.  Ignored if $phpNukeCompatible is set
//      to FALSE.  This should be the folder where your Nuke site
//      is installed.  Do not include a trailing slash.
// $phpWebThingsRoot
//      Same thing, only for phpWebThings.
//
// Both default to ''.
//
// Example:
//      $phpNukeRoot = '/www/mysite.com/nuke';
//      $phpWebThingsRoot = '/www/mysite.com/webthings';
//

$phpNukeRoot = '';
$phpWebThingsRoot = '';


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


// -----------------------------------------------------------------
//                    MODIFYING THE INCLUDE PATH
// -----------------------------------------------------------------
//
// This is not normally required, but in some peculiar setups you
// are forced to use non-real paths in order to use the include()
// function.  That is, you have a real path, for instance:
//      /u25/vhost/www12345/www/mig/myfile.php
// but the ISP has PHP installed such that you need to tell include()
// to use this virtual path instead to the same file:
//     /mig/myfile.php
//
// To address this, the following three options exist.  Do not use
// these unless you have to, and know you have to.  If you don't
// need to use them and do anyway, you'll probably break Mig.
//
// $pathConvertFlag
//     This is a boolean to determine if conversion is needed.  Only
//     set this to TRUE if you know you need to do so.
//
// Defaults to FALSE.
//
// Example:
//     $pathConvertFlag = FALSE;
//

$pathConvertFlag = FALSE;

//
// $pathConvertRegex
//     This is a regular expression string, used to tell Mig how to
//     modify your include path.  If you don't know regular expressions,
//     here's probably all you need to know:
//     
//     ^    means "beginning of string"
//     .*   is a wildcard for any number of characters of any kind
//          (note - it will also match 0 characters in some cases)
//
//     Going back to the earlier example, if you want to start out
//     with this:
//         /u25/vhost/www12345/www/mig/myfile.php
//     and end up with this:
//         /mig/myfile.php
//
//     You could define:
//         $pathConvertFlag = TRUE;
//         $pathConvertRegex = '^.*/www/';
//         $pathConvertTarget = '/';
//
//     So the regex would match this:  /u25/vhost/www12345/www/
//     and replace it with a single slash... resulting in:
//         /mig/myfile.php
//
// Defaults to an empty string.
//
// Example:
//     $pathConvertRegex = '^.*/www/';
//

$pathConvertRegex = '';

//
// $pathConvertTarget
//     This is the target string, which replaces the portion matched by
//     the regex.  Usually this should be '/', but it can be changed.
//     See the notes for $convertPathRegex (above) for more details.
//
// Defaults to an empty string.
//
// Example:
//     $pathConvertTarget = '/';
//

$pathConvertTarget = '';


?>
