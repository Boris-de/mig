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

$pathConvert = new ConvertIncludePath($pathConvertFlag, $pathConvertRegex, $pathConvertTarget);

// Include config file, making sure to modify the include path if appropriate.
if ($configFile) {
    include($pathConvert->convertIncludePath($configFile));
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
$mig_config['maintAddr']                        = $maintAddr;
$mig_config['maxFolderColumns']                 = $maxFolderColumns;
$mig_config['maxThumbColumns']                  = $maxThumbColumns;
$mig_config['maxThumbRows']                     = $maxThumbRows;
$mig_config['version']                          = $version;
$mig_config['distURL']                          = $distURL;
$mig_config['exifFormatString']                 = $exifFormatString;
$mig_config['largeLinkFromMedium']              = $largeLinkFromMedium;
$mig_config['largeLinkUseBorders']              = $largeLinkUseBorders;

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
if ($jump && isset($jumpMap[$jump]) && $SERVER_NAME) {
    header("Location: $URI_SCHEME://$SERVER_NAME:$SERVER_PORT" . $mig_config['baseurl']
         . "?$jumpMap[$jump]");
    exit;
}

// Jump-tag using PATH_INFO rather than "....?jump=x" URI
if (isset($PATH_INFO) && isset($jumpMap[$PATH_INFO]) && $SERVER_NAME) {
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

function _get_magic_quotes_gpc() {
    return function_exists('get_magic_quotes_gpc') ? @get_magic_quotes_gpc() : 0;
}

// Get rid of \'s if magic_quotes_gpc is turned on (causes problems).
if (_get_magic_quotes_gpc() == 1) {
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
if (_get_magic_quotes_gpc() == 1 && $image) {
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
    /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
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

printPage($currDir, $pathConvert, $image);

?>
