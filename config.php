<?php

// $Id$
//
// config.php - Configuration file for Mig
//
// Copyright 2000-2003 Daniel M. Lowe <dan@tangledhelix.com>
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
// Defaults to "My Photo Album".
//
// Example:
//     $pageTitle = "My Photo Album";
//

$pageTitle = "My Photo Album";


//
// $maintAddr
//     Email address of the person who runs this album.  This is the
//     global setting.  A per-folder maintAddr can be defined using the
//     MaintAddr keyword in a mig.cf file.
//
// Defaults to "webmaster@mydomain.com".
//
// Example:
//     $maintAddr = "webmaster@mydomain.com";
//

$maintAddr = "webmaster@mydomain.com";


//
// $homeLink
//     "Home" link (optional) - a "home" page to "go back" to from the
//     main page.  Leave blank if you don't want one.
//
// No default.
//
// Example:
//     $homeLink = "http://mydomain.com/";
//

$homeLink = "";


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
//     $homeLabel = "My Home Page";
//

$homeLabel = "";


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
// Defaults to "thumbs".
//
// Example:
//     $thumbSubdir = "thumbs";
//

$thumbSubdir = "thumbs";


//
// $randomFolderThumbs
//      If TRUE, instead of the generic folder icon, a represenative
//      thumbnail from the folder is shown.  If Mig is unable to find a
//      thumbnail to use, it will use the generic folder icon instead.
//      Unless there are folders whose subfolders and their subfolders
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
// $useRealRandThumbs
//      If TRUE, a real random selection is done.  It used to be that
//      Mig would just look for the first thumbnail it could find and
//      use that.  A lot of people, including the author, thought that
//      sucked, so $useRealRandThumbs was introduced which will find
//      all available thumbnails and pick one randomly each time the
//      script is called.
//
//      This obviously results in more I/O and on large galleries could
//      cause a problem with execution time.  So I didn't "fix the bug"
//      entirely, I made it an optional behavior.  By default, real
//      random behavior is used, but it can be turned off by setting this
//      to FALSE.
//
//      Generally, you want this to be TRUE unless you have trouble with
//      it (most likely to happen if you have a slow server, a busy server,
//      slow disks, or very large galleries).
//
//      And of course this option is ignored if $randomFolderThumbs is
//      set to FALSE.
//
// Defaults to TRUE.
// 
// Example:
//      $useRealRandThumbs = TRUE;
//

$useRealRandThumbs = TRUE;


//
// $useLargeImages
//      Boolean to turn large image support on or off.
//
//      Only turn this feature on if you intend to have three sizes of
//      image - the thumbnail, the regular size version, and a full-size
//      "large" version, such as the original file from the camera.  Most
//      users will want to leave this turned off.
//
//      If set to TRUE, Mig will look for a "large" version of the image
//      in $largeSubdir.  If one is found, a link will be generated
//      pointing to the large image.
//
//      If $largeLinkFromMedium is TRUE, clicking on the medium image
//      will also take the user to the large image.  If you don't want
//      a link printed, but just want to have people click on the medium
//      image only, remove the %%largeLink%% tag from your image.html
//      or mig_image.php template file.
//
//      To use this feature, make sure the following tags are defined in
//      your image.html or mig_image.php template:
//
//          %%largeLink%%       [optional, see above]
//          %%largeHrefStart%%
//          %%largeHrefEnd%%
//          %%largeLinkBorder%%
//
//      Also, copy large.html (or for PHP-Nuke etc, use mig_large.php)
//      to your templates folder.
//
// Defaults to FALSE.
//
// Example:
//      $useLargeImages = FALSE;
//

$useLargeImages = FALSE;


//
// $largeSubdir
//      Name of subdirectory to use for "large" images.  This option is
//      ignored if $useLargeImages is FALSE.
//
// Defaults to "large".
//
// Example:
//      $largeSubdir = "large";
//

$largeSubdir = "large";


//
// $largeLinkFromMedium
//      If TRUE, when viewing a normal image, clicking the image will
//      take you to the large version of the image (if present).
//
//      If FALSE, clicking on the normal image will do nothing, regardless
//      of the presence or absence a large version.
//
//      In either case, if the template tag %%largeLink%% is used, a link
//      to the image will be printed along with the other navigational
//      links on the page.
//      
// Defaults to TRUE.
//
// Example:
//      $largeLinkFromMedium = TRUE;
//

$largeLinkFromMedium = TRUE;


//
// $largeLinkUseBorders
//      When $largeLinkFromMedium is TRUE, this setting determines whether
//      to show the link border around a medium image or not.  If TRUE,
//      the border is shown, if FALSE it is not shown.
//
// Defaults to FALSE.
//
// Example:
//      $largeLinkUseBorders = FALSE;
//

$largeLinkUseBorders = FALSE;


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
//     subdirectories is recommended, so avoid this option (along with
//     $markerLabel) if possible.
//
// Defaults to "suffix".
//
// Example:
//     $markerType = "suffix";
//

$markerType = "suffix";


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
// Defaults to "th".
//
// Example:
//     $markerLabel = "th";
//

$markerLabel = "th";


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
// $FileInfoFormatString
//     Defines the format of the ImageInfo:
//
//     Valid items are:
//       %n = Filename
//       %s = FileSize
//       %i = ImageSize
//
// Examples:
//   Everything is shown (old mig behaviour)
//   $FileInfoFormatString = "%n<br>(%i, %s)";
//
// Default to %n<br>(%i, %s)
//
// FIXME: is $suppressImageInfo needed any more? just set $ImageInfoFormatString to ""...

$FileInfoFormatString = "[%i, %s]";

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
//      $showShortOnThumbPage = FALSE;
//

$showShortOnThumbPage = FALSE;


//
// $omitImageName
//      If set to FALSE, the image name is shown in the "path" line
//      at the top of each page.  If set TRUE, the image name is
//      omitted.  The "(#x of y)" in the same line in image views
//      is not affected by this setting.  If you don't want that to
//      show up, get rid of %%currPos%% in your image.html template.
//
// Defaults to FALSE.
//
// Example:
//     $omitImageName = FALSE;
//

$omitImageName = FALSE;


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
//     $thumbExt = "gif";
//

$thumbExt = "";


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
// Defaults to "default".
//
// Example:
//     $sortType = "default";
//

$sortType = "default";


//
// $folderSortType
//      If present, overrides the value of $sortType when sorting folders.
//      Has the same possible values as $sortType (see above).
//
// Defaults to "default".
//
// Example:
//      $folderSortType = "default";
//

$folderSortType = "default";


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
// Defaults to "reuse".
//
// Example:
//     $imagePopType = "reuse";
//

$imagePopType = "reuse";


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
// $imagePopMaxWidth
//      The maximum initial width for a popup window (in pixels).
//      
// Defaults to 640.
//
// Example:
//      $imagePopMaxWidth = 640;
//
//

$imagePopMaxWidth = 640;


//
// $imagePopMaxHeight
//      The maximum initial height for a popup window (in pixels).
//
// Defaults to 480.
//
// Example:
//      $imagePopMaxHeight = 480;
//
//

$imagePopMaxHeight = 480;


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
// $ignoreDotDirectories
//      If $ignoreDotDirectories is set to TRUE, any directory with a
//      name starting with '.' (dot or period) will be ignored while
//      looking at building folder lists.
//
// Defaults to FALSE.
//
// Example:
//      $ignoreDotDirectories = FALSE;
//

$ignoreDotDirectories = FALSE;


//
// $exifFormatString
//     Defines the display format for EXIF data blocks.  Sections are
//     separated by | characters.  For example:
//     
//         "|%c<hr>|%M %D %Y, %T - |%m<br>|%l |%s |%a|"
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
// Defaults to "|%c|" - which is just embedded comments.
//
// Examples:
//
//  Just the date and time:
//     $exifFormatString = "|%M %D %Y, %T|";
//
//  What used to be called $viewCamInfo would look like this:
//     $exifFormatString = "|%c<hr>|%m<br>|ISO %i |%l |%s |%a |(%f)|";
//
//  What used to be $viewCamInfo and $viewDateInfo together is:
//     "|%c<hr>|%M %D %Y, %T - |%m<br>|ISO %i |%l |%s |%a |(%f)|";
//

$exifFormatString = "|%c|";


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
//       zh      Traditional Chinese (big5)
//       cz      Czech
//
//     You can also define a language by using mig_dl in the URL.  Just
//     add a mig_dl={lang} to the query string.  For example, if you have
//     a default language of english (en) you can have a spanish translation
//     by adding mig_dl=es to your URL as a parameter.  Examples:
//
//         http://mysite.com/gallery/index.php?currDir=./My_Stuff
//
//     Change to:
//     
//         http://mysite.com/gallery/index.php?currDir=./My_Stuff&mig_dl=es
//
//     In this way you can have multiple translations of your site by just
//     having a different hyperlink for each one.  See the install document
//     for more information.
//     
//     See the template document for information on the 'newLang' template
//     keyword which lets you easily build links into your templates for
//     other languages.
//
//     If you want to translate Mig into another language, please contact
//     me via email (dan@tangledhelix.com).
//
//     (Note: this variable used to be called $language, but that is
//     deprecated and it should be used as $mig_language as of
//     version 1.2.2)
//
// Defaults to "en".
//
// Example:
//     $mig_language = "en";
//

$mig_language = "en";


//
// $jumpMap
//     You can add things to your "jump" map - see the "jump" document.
//
// Default is no jump map.
//
// Example:
//     $jumpMap["example"] = "currDir=./Mig_Example_Gallery";
//


// -----------------------------------------------------------------
// CONTENT MANAGEMENT SYSTEMS (Portals)
//
// Compatible with:
//
//      PHP-Nuke                http://phpnuke.org/
//      PostNuke                http://postnuke.com/
//      phpWebSite              http://phpwebsite.appstate.edu/
//      phpWebThings            http://phpdbform.com/
//      Xoops                   http://xoops.org/
//      Geeklog                 http://geeklog.sourceforge.net/
//
// -----------------------------------------------------------------
//
// $phpNukeCompatible
//      Set to TRUE if you're using PHP-Nuke, PostNuke or phpWebSite.
// $phpWebThingsCompatible
//      Set to TRUE if you're using phpWebThings.
// $mig_xoopsCompatible
//      Set to TRUE if you're using XOOPS.
// $mig_GeeklogCompatible
//      Set to TRUE if you're using Geeklog.
//
//      Obviously you only want to set ONE of these to TRUE.
//
// All of them default to FALSE.
// 
// Example:
//      $phpNukeCompatible = FALSE;
//      $phpWebThingsCompatible = FALSE;
//      $mig_xoopsCompatible = FALSE;
//      $mig_GeeklogCompatible = FALSE;
//

$phpNukeCompatible = FALSE;
$phpWebThingsCompatible = FALSE;
$mig_xoopsCompatible = FALSE;
$mig_GeeklogCompatible = FALSE;

//
// $phpNukeRoot
//      Set to the root directory of your PHP-Nuke, PostNuke or
//      phpWebSite system.  Ignored if $phpNukeCompatible is set
//      to FALSE.  This should be the folder where your Nuke site
//      is installed.  Do not include a trailing slash.
// $phpWebThingsRoot
//      Same thing, only for phpWebThings.  Ignored if
//      $phpWebThingsCompatible is FALSE.
// $mig_xoopsRoot
//      Same thing, only for XOOPS.  Ignored if $mig_xoopsCompatible is
//      FALSE.
// $mig_GeeklogRoot
//      Same thing, only for Geeklog.  Ignored if $mig_GeeklogCompatible
//      is FALSE.
//
// $phpNukeRoot, $phpWebThingsRoot and $mig_GeeklogRoot default to "".
//
// $mig_xoopsRoot defaults to "../..".
//
// Examples:
//      $phpNukeRoot = "/www/mysite.com/nuke";
//      $phpWebThingsRoot = "/www/mysite.com/webthings";
//      $mig_xoopsRoot = "/www/mysite.com/xoops";
//      $mig_GeeklogRoot = "/www/mysite.com/geeklog";
//

$phpNukeRoot = "";
$phpWebThingsRoot = "";
$mig_xoopsRoot = "../..";
$mig_GeeklogRoot = "";

//      
// $mig_xoopsRBlockForImage
// $mig_xoopsRBlockForFolder
//      Flags to show or hide the right-hand side blocks in image and
//      folder modes (in XOOPS)
//      
// They default to 0 and 1, show right block for folders, hide for images.
//
// Examples:
//      $mig_xoopsRBlockForImage = 0;
//      $mig_xoopsRBlockForFolder = 1;
//

$mig_xoopsRBlockForImage = 0;
$mig_xoopsRBlockForFolder = 1;

//
// $mig_GeeklogRBlockForImage
// $mig_GeeklogRBlockForFolder
//      Flags to show or hide the right-hand side blocks in image and
//      folder modes (in Geeklog)
//
// They default to 0 and 1, show right block for folders, hide for images.
//
// Examples:
//      $mig_GeeklogRBlockForImage = 0;
//      $mig_GeeklogRBlockForFolder = 1;
//

$mig_GeeklogRBlockForImage = 0;
$mig_GeeklogRBlockForFolder = 1;


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
//     $protect["./Example_Gallery"]["joe"] = "IBDXWbkBirMfU";
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
// $pathConvertFlag, $pathConvertRegex and $pathConvertTarget
// have moved to index.php.  This is because they are used to
// include config.php, so obviously they can't be INSIDE it.
//

?>
