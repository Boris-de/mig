<?php

error_reporting($error_reporting);

// URL to use to call myself again
if (isset($_SERVER['PHP_SELF'])) {
    $mig_config['baseurl'] = $_SERVER['PHP_SELF'];
} elseif (isset($HTTP_SERVER_VARS['PHP_SELF'])) {
    $mig_config['baseurl'] = $HTTP_SERVER_VARS['PHP_SELF'];
} elseif (isset($PHP_SELF)) {
    $mig_config['baseurl'] = $PHP_SELF;
} elseif (isset($_SERVER['SCRIPT_NAME'])) {
    $mig_config['baseurl'] = $_SERVER['SCRIPT_NAME'];
} elseif (isset($HTTP_SERVER_VARS['SCRIPT_NAME'])) {
    $mig_config['baseurl'] = $HTTP_SERVER_VARS['SCRIPT_NAME'];
} elseif (isset($SCRIPT_NAME)) {
    $mig_config['baseurl'] = $SCRIPT_NAME;
} else {
    print 'FATAL ERROR: Could not set baseurl';
    exit;
}

// Base directory of installation
if (isset($_SERVER['PATH_TRANSLATED'])) {
    $mig_config['basedir'] = $_SERVER['PATH_TRANSLATED'];
} elseif (isset($HTTP_SERVER_VARS['PATH_TRANSLATED'])) {
    $mig_config['basedir'] = $HTTP_SERVER_VARS['PATH_TRANSLATED'];
} elseif (isset($PATH_TRANSLATED)) {
    $mig_config['basedir'] = $PATH_TRANSLATED;
} elseif (isset($_SERVER['SCRIPT_FILENAME'])) {
    $mig_config['basedir'] = $_SERVER['SCRIPT_FILENAME'];
} elseif (isset($HTTP_SERVER_VARS['SCRIPT_FILENAME'])) {
    $mig_config['basedir'] = $HTTP_SERVER_VARS['SCRIPT_FILENAME'];
} elseif (isset($SCRIPT_FILENAME)) {
    $mig_config['basedir'] = $SCRIPT_FILENAME;
} else {
    print 'FATAL ERROR: Can not set basedir';
    exit;
}

// Strip down to just directory name
$mig_config['basedir'] = dirname($mig_config['basedir']);

// Strip extra slashes out of basedir if appropriate
// This is basically for Windows SMB shares
if (preg_match('#^[\\\\]{2}#i', $mig_config['basedir'])) {
    $mig_config['basedir'] = stripslashes($mig_config['basedir']);
}

// Locate and load configuration
if (file_exists($mig_config['basedir'].'/mig/config.php')) {
    // Found it - we're in Nuke mode
    $configFile = $mig_config['basedir'].'/mig/config.php';
} elseif (file_exists($mig_config['basedir'].'/config.php')) {
    // Found it - regular mode
    $configFile = $mig_config['basedir'].'/config.php';
}

// Include config file, making sure to modify the include path if appropriate.
if ($configFile) {
    include(convertIncludePath($pathConvertFlag, $configFile,
                               $pathConvertRegex, $pathConvertTarget));
}

//for old compatibility: remove in mig 2.0:
if ($suppressImageInfo == TRUE) {
    $fileInfoFormatString['image'] = "%n";
    $fileInfoFormatString['audio'] = "%n";
    $fileInfoFormatString['video'] = "%n";
}

// Fetch some settings into $mig_config
$mig_config['commentfileperimage']              = $commentFilePerImage;
$mig_config['commentfileshortcomments']         = $commentFileShortComments;
$mig_config['fileinfoformatstring']             = $fileInfoFormatString;
$mig_config['foldernamelength']                 = $folderNameLength;
$mig_config['foldersorttype']                   = $folderSortType;
$mig_config['homelabel']                        = $homeLabel;
$mig_config['homelink']                         = $homeLink;
$mig_config['ignoredotdirectories']             = $ignoreDotDirectories;
$mig_config['imagepoplocationbar']              = $imagePopLocationBar;
$mig_config['imagepopmaxheight']                = $imagePopMaxHeight;
$mig_config['imagepopmaxwidth']                 = $imagePopMaxWidth;
$mig_config['imagepoptoolbar']                  = $imagePopToolBar;
$mig_config['imagepoptype']                     = $imagePopType;
$mig_config['imagepopup']                       = $imagePopup;
$mig_config['largesubdir']                      = $largeSubdir;
$mig_config['nextformatstring']                 = $nextFormatString;
$mig_config['nothumbs']                         = $noThumbs;
$mig_config['omitimagename']                    = $omitImageName;
$mig_config['randomfolderthumbs']               = $randomFolderThumbs;
$mig_config['pagetitle']                        = $pageTitle;
$mig_config['prevformatstring']                 = $prevFormatString;
$mig_config['showshortonthumbpage']             = $showShortOnThumbPage;
$mig_config['sorttype']                         = $sortType;
$mig_config['suppressalttags']                  = $suppressAltTags;
$mig_config['suppressimageinfo']                = $suppressImageInfo;
$mig_config['thumbext']                         = $thumbExt;
$mig_config['thumbsubdir']                      = $thumbSubdir;
$mig_config['uselargeimages']                   = $useLargeImages;
$mig_config['userealrandthumbs']                = $useRealRandThumbs;
$mig_config['usethumbsubdir']                   = $useThumbSubdir;
$mig_config['viewfoldercount']                  = $viewFolderCount;
$mig_config['imageFilenameRegexpr']             = $imageFilenameRegexpr;
$mig_config['currDirNameRegexpr']               = $currDirNameRegexpr;
$mig_config['httpContentType']                  = $httpContentType;
$mig_config['music_icon']                       = $music_icon;
$mig_config['movie_icon']                       = $movie_icon;
$mig_config['folder_icon']                      = $folder_icon;
$mig_config['nothumb_icon']                     = $nothumb_icon;
$mig_config['showTotalImagesString']            = $showTotalImagesString;
$mig_config['image_extensions']                 = $image_extensions;
$mig_config['video_extensions']                 = $video_extensions;
$mig_config['audio_extensions']                 = $audio_extensions;

function getVariable($name, $arr1, $arr2, $default = NULL) {
    $result = $default;
    if (isset($arr1[$name])) {
        $result = $arr1[$name];
    } elseif (isset($arr2[$name])) {
        $result = $arr2[$name];
    }
    return $result;
}

function getHttpGetVariable($name, $default = NULL) {
    $get_vars = isset($HTTP_GET_VARS) ? $HTTP_GET_VARS : array();
    return getVariable($name, $_GET, $get_vars, $default);
}

function getHttpServerVariable($name, $default = NULL) {
    $server_vars = isset($HTTP_SERVER_VARS) ? $HTTP_SERVER_VARS : array();
    return getVariable($name, $_SERVER, $server_vars, $default);
}

// Jump has to come before currDir redirect to work
if (! isset($jump) || ! $jump) {
    $jump = getHttpGetVariable('jump', FALSE);
}

if (! isset($SERVER_NAME)) {
    $SERVER_NAME = getHttpServerVariable('SERVER_NAME');
}

if (! isset($SERVER_PORT)) {
    $SERVER_PORT = getHttpServerVariable('SERVER_PORT', '80');
}

if (! isset($PATH_INFO)) {
    $PATH_INFO = getHttpServerVariable('PATH_INFO');
}

$URI_SCHEME = getHttpServerVariable('HTTPS') === 'on' ? 'https' : 'http';

// Is this a jump-tag URL?
if ($jump && $jumpMap[$jump] && $SERVER_NAME) {
    header("Location: $URI_SCHEME://$SERVER_NAME:$SERVER_PORT" . $mig_config['baseurl']
         . "?$jumpMap[$jump]");
    exit;
}

// Jump-tag using PATH_INFO rather than "....?jump=x" URI
if (isset($PATH_INFO) && $jumpMap[$PATH_INFO] && $SERVER_NAME) {
    header("Location: $URI_SCHEME://$SERVER_NAME:$SERVER_PORT" . $mig_config['baseurl']
         . "?$jumpMap[$PATH_INFO]");
    exit;
}

//moved this some lines up... need it for checking if the image-file exists. wmk

$mig_config['albumdir'] = $mig_config['basedir'] . '/albums';   // Where albums live
// If you change the directory here also make sure to change $albumURLroot



// Get currDir.  If there isn't one, default to "."
$currDir = getHttpGetVariable('currDir');
if (! $currDir) {
    if ($SERVER_NAME) {
        header("Location: $URI_SCHEME://$SERVER_NAME:$SERVER_PORT" 
             . $mig_config['baseurl'] . '?currDir=.');
        exit;
    }
}

// Get rid of \'s if magic_quotes_gpc is turned on (causes problems).
if (get_magic_quotes_gpc() == 1) {
    $currDir = stripslashes($currDir);
}

// Look at currDir from a security angle.  Don't let folks go outside
// the album directory base
if (strstr($currDir, '..') || !preg_match($mig_config['currDirNameRegexpr'], $currDir)) {
    print 'SECURITY VIOLATION - ABANDON SHIP';
    exit;
}

// Try to validate currDir
//     Must be either "." (root) or,
//     must begin with "./" and dot or slash can't follow that
//     for at least two positions.
//
if ( $currDir != '.' && ! preg_match('#^./[^/][^/]*#', $currDir) ) {
    print 'ERROR: \$currDir is invalid.  Exiting.';
    exit;
}

// currDir may not end in / unless it is './' in its entirety
if ( $currDir != './' && preg_match('#/$#', $currDir) ) {
    print "ERROR: \$currDir is invalid.  Exiting.";
    exit;
}

// Strip URL encoding
$currDir = rawurldecode($currDir);

// Get image, if there is one.
if (! isset($image)) {
    $image = getHttpGetVariable('image');
}

// Get rid of \'s if magic_quotes_gpc is turned on (causes problems).
if ((get_magic_quotes_gpc() == 1) && ($image)) {
        $image = stripslashes($image);
}

// Look at $image from a security angle.
// Don't let folks go outside the album directory base
// Don't let folks define ANY directory here
if (strstr($image, '..') || !preg_match($mig_config['imageFilenameRegexpr'], $image)) {
    print 'ERROR: $image is invalid.  Exiting.';
    exit;
}

$mig_config['image'] = htmlentities($image);

// check if the image exists...

if (($mig_config['image'])AND(!file_exists($mig_config['albumdir']."/$currDir/".$mig_config['image']))){
    echo "ERROR: ".$currDir."/".$mig_config['image']." is invalid.  Exiting.";
    exit;
}




// Get pageType.  If there isn't one, default to "folder"
if (! isset($pageType)) {
    $pageType = getHttpGetVariable('pageType', 'folder');
}

// only allow one of the predefined values
$allowedTypes = array( "image" => 1, "folder" => 1, "large" => 1, "" => 1);

if(!isset($allowedTypes[$pageType])) {
	echo 'ERROR: $pageType is invalid.  Exiting.';
	exit;
}

unset($allowedTypes);

$mig_config['pagetype'] = $pageType;


if (! isset($startFrom)) {
    $startFrom = getHttpGetVariable('startFrom', 0) + 0;
}

// only allow digits for $startFrom
$mig_config['startfrom'] = $startFrom;

// use language set specified in URL, if one was.
if (! isset($mig_dl)) {
    $mig_dl = getHttpGetVariable('mig_dl');
}
// Only use it if we find it - otherwise fall back to default language
if ($mig_dl && $mig_config['lang_lib'][$mig_dl]) {
    $mig_language = $mig_dl;
} else {
    $mig_dl = NULL;        // destroy it so it isn't used in URLs
}
$mig_config['mig_dl'] = $mig_dl;


// Grab appropriate language from library
$mig_config['lang'] = $mig_config['lang_lib'][$mig_language];

// Backward compatibility with older config.php/mig.cfg versions
if (isset($maxColumns)) {
    $maxThumbColumns = $maxColumns;
}

// Turn off magic_quotes_runtime (causes trouble with some installations)
// (This method is deprecated as of 5.3, so only call it if the function exists)
if (function_exists('set_magic_quotes_runtime')) {
  @set_magic_quotes_runtime(0);
}


//
// Handle any password authentication needs
//

$workCopy = $currDir;     // temporary copy of currDir

while ($workCopy) {

    if (isset($protect[$workCopy])) {
        die('password protection is not supported anymore');
    }

    // if $workCopy is already down to "." just nullify to end loop
    if ($workCopy == '.') {
        $workCopy = FALSE;
    } else {
        // parse $workCopy down one directory at a time
        // so we can check back all the way to "."
        $workCopy = preg_replace('#/[^/]+$#', '', $workCopy);
    }
}

// send Content-Type
if($httpContentType) {
    header('Content-Type: '.$httpContentType);
}

// Where templates live
$mig_config['templatedir'] = $mig_config['basedir'] . '/templates';

// baseURL with the scriptname torn off the end
$baseHref = preg_replace('#/[^/]+$#', '', $mig_config['baseurl']);

// Location of image library (for instance, where icons are kept)
$mig_config['imagedir'] = $baseHref . '/images';

// Root where album images are living
$mig_config['albumurlroot'] = $baseHref . '/albums';
// NOTE: Sometimes Windows users have to set this manually, like:
// $mig_config['albumurlroot'] = '/mig/albums';

// Well, GIGO... set default to sane if someone screws up their
// config file

if ($markerType != 'prefix' && $markerType != 'suffix' ) {
    $markerType = 'suffix';
}
$mig_config['markertype'] = $markerType;

if (! $markerLabel) {
    $markerLabel = 'th';
}
$mig_config['markerlabel'] = $markerLabel;

// Override folder sort if one's not present
if (! $mig_config['foldersorttype']) {
    $mig_config['foldersorttype'] = $mig_config['sorttype'];
}

// Fetch mig.cf information
list($presort_dir, $presort_img, $desc, $short_desc, $bulletin,
     $ficons, $folderTemplate, $folderFolderCols,
     $folderThumbCols, $folderThumbRows, $folderMaintAddr)
  = parseMigCf($mig_config['albumdir']."/$currDir");

// Set per-folder $maintAddr if one was defined
if ($folderMaintAddr) {
    $maintAddr = $folderMaintAddr;
}

// strip URL encoding here too
$mig_config['image'] = rawurldecode($mig_config['image']);

// if pageType is "folder") generate a folder view

if ($mig_config['pagetype'] == 'folder') {

    // Determine which template to use
    if ($folderTemplate) {
        $templateFile = $folderTemplate;
    } else {
        $templateFile = $mig_config['templatedir'] . '/folder.html';
    }

    // Determine columns and rows to use
    if ($folderFolderCols) {
        $maxFolderColumns = $folderFolderCols;
    }

    if ($folderThumbCols) {
        $maxThumbColumns = $folderThumbCols;
    }

    // Generate some HTML to pass to the template printer

    // list of available folders
    $folderList = buildDirList($currDir, $maxFolderColumns, $presort_dir, $ficons);
    // list of available images
    $imageList = buildImageList($currDir, $maxThumbColumns, $maxThumbRows,
                                $presort_img, $desc, $short_desc);

    // Only frame the lists in table code when appropriate

    // Set style of table, either with text or thumbnails
    if ($mig_config['randomfolderthumbs']) {
        $folderTableClass = 'folderthumbs';
    } else {
        $folderTableClass = 'foldertext';
    }

    // no folders or images - print the "no contents" line
    if ($folderList == 'NULL' && $imageList == 'NULL') {
        $folderList = $mig_config['lang']['no_contents'];
        $tablesummary = 'Folders Frame';
        $folderList = buildTable($folderList, $folderTableClass,
                                 $tablesummary);
        $imageList = '';

    // images, no folders.  Frame the imagelist in a table
    } elseif ($folderList == 'NULL' && $imageList != 'NULL') {
        $folderList = '';
        /*$tablesummary = 'Images Frame';
        $tableclass = 'image';
        $imageList = buildTable($imageList, $tableclass, $tablesummary);*/

    // folders but no images.  Frame the folderlist in a table
    } elseif ($imageList == 'NULL' && $folderList != 'NULL') {
        $imageList = '';
        $tablesummary = 'Folders Frame';
        $folderList = buildTable($folderList, $folderTableClass,
                                 $tablesummary);

    // We have folders and we have images, so frame both in tables.
    } else {
        $tablesummary = 'Folders Frame';
        $folderList = buildTable($folderList, $folderTableClass,
                                 $tablesummary);
        /*$tablesummary = 'Images Frame';
        $tableclass = 'image';
        $imageList = buildTable($imageList, $tableclass, $tablesummary);*/
    }

    // We have a bulletin
    if ($bulletin != '') {
        $tablesummary = 'Bulletin Frame" width="60%';  //<--- kludge for now
        $tableclass = 'desc';
        $bulletin = '<center>' . $bulletin . '</center>';
        $bulletin = buildTable($bulletin, $tableclass, $tablesummary);
    }

    // build the "back" link
    $backLink = buildBackLink($currDir, 'back');

    // build the "you are here" line
    $youAreHere = buildYouAreHere($currDir);

    // newcurrdir is currdir without the leading "./"
    $newCurrDir = getNewCurrDir($currDir);

    // parse the template file and print to stdout
    printTemplate($templateFile, $version, $maintAddr,
                  $folderList, $imageList, $backLink, '',
                  $newCurrDir, '', '', '', $bulletin,
                  $youAreHere, $distURL, $pathConvertFlag,
                  $pathConvertRegex, $pathConvertTarget, '', '', '', '');


// If pageType is "image", show an image

} elseif ($mig_config['pagetype'] == 'image') {

    // Trick back link into going to the right place by adding
    // a bogus directory at the end
    $backLink = buildBackLink("$currDir/blah", 'up');

    // Get the "next image" and "previous image" links, and the current
    // position (#x of y)
    $Links = array ();
    $Links = buildNextPrevLinks($currDir, $presort_img);
    list($nextLink, $prevLink, $currPos) = $Links;

    // Get image description
    if ($mig_config['commentfileperimage']) {
        list($x, $description) = getImageDescFromFile($image, $currDir);
        // If getImageDescFromFile() returned false, get the normal
        // comment if there is one.
        if (! $description) {
            list($x, $description) = getImageDescription($image, $desc, $short_desc);
        }
    } else {
        list($x, $description) = getImageDescription($image, $desc, $short_desc);
    }

    $exifDescription = getExifDescription($currDir, $exifFormatString);

    // If there's a description but no exifDescription, just make the
    // exifDescription the description
    if ($exifDescription && ! $description) {
        $description = $exifDescription;
        unset($exifDescription);
    }

    // If both descriptions are non-NULL, separate them with an <HR>
    if ($description && $exifDescription) {
        $description .= '<hr>';
        $description .= $exifDescription;
    }

    // Build the "you are here" line
    $youAreHere = buildYouAreHere($currDir, $mig_config['omitimagename']);

    // Which template to use.
    $templateFile = $mig_config['templatedir'] . '/image.html';

    // newcurrdir is currdir without the leading "./"
    $newCurrDir = getNewCurrDir($currDir);

    $largeLink = '';
    $largeHrefStart = '';
    $largeHrefEnd = '';
    $largeLinkBorder = '';
    if ($mig_config['uselargeimages'] &&
            file_exists($mig_config['albumdir']."/$currDir/"
                      . $mig_config['largesubdir']
                      . '/'.$mig_config['image']))
    {
        $largeLink = buildLargeLink($currDir);

        // Only build this link if we plan to use it
        if ($largeLinkFromMedium) {
            $largeHrefStart = buildLargeHrefStart($currDir);
            $largeHrefEnd = '</a>';
        }

        // Use a border?
        if (! $largeLinkUseBorders) {
            $largeLinkBorder = ' border="0"';
        }
    }

    // Send it all to the template printer to dump to stdout
    printTemplate($templateFile, $version, $maintAddr, '', '', $backLink,
                  $currDir, $newCurrDir, $prevLink, $nextLink, $currPos,
                  $description, $youAreHere, $distURL, $pathConvertFlag, $pathConvertRegex,
                  $pathConvertTarget, $largeLink, $largeHrefStart, $largeHrefEnd,
                  $largeLinkBorder);

// If the pageType is "large", show a large image

} elseif ($mig_config['pagetype'] == 'large') {

    // Trick the back link into going to the right place by adding
    // a bogus directory at the end
    $backLink = buildBackLink("$currDir/blah", 'up');

    // Get the "next image" and "previous image" links, and the current
    // position (#x of y)
    $Links = array ();
    $Links = buildNextPrevLinks($currDir, $presort_img);
    list($nextLink, $prevLink, $currPos) = $Links;

    // Get image description
    if ($mig_config['commentfileperimage']) {
        list($x, $description) = getImageDescFromFile($currDir, $image);
        // If getImageDescFromFile() returned false, get the normal
        // comment if there is one.
        if (! $description) {
            list($x, $description) = getImageDescription($image, $desc,$short_desc);
        }
    } else {
        list($x, $description) = getImageDescription($image, $desc,$short_desc);
    }

    $exifDescription = getExifDescription($currDir,$exifFormatString);

    // If there's a description but no exifDescription, just make the
    // exifDescription the description
    if ($exifDescription && ! $description) {
        $description = $exifDescription;
        unset($exifDescription);
    }

    // If both descriptions are non-NULL, separate them with an <HR>
    if ($description && $exifDescription) {
        $description .= '<hr />';
        $description .= $exifDescription;
    }

    // Build the "you are here" line
    $youAreHere = buildYouAreHere($currDir, $mig_config['omitimagename']);

    // Which template to use
    $templateFile = $mig_config['templatedir'] . '/large.html';

    // newcurrdir is currdir without the leading "./"
    $newCurrDir = getNewCurrDir($currDir);

    // Send it all to the template printer to dump to stdout
    printTemplate($templateFile, $version, $maintAddr, '', '', $backLink,
                  $currDir, $newCurrDir, $prevLink, $nextLink, $currPos,
                  $description, $youAreHere, $distURL, $pathConvertFlag, $pathConvertRegex,
                  $pathConvertTarget, '', '', '', '');
}

?>
