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
    exit('FATAL ERROR: Could not set baseurl');
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
    exit('FATAL ERROR: Can not set basedir');
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
if (isset($configFile)) {
    $configFile = $pathConvert->convertIncludePath($configFile);
    include($configFile);
}

$mig_config['albumdir'] = $mig_config['basedir'] . $albumRoot;   // Where albums live

// apply open_basedir restrictions (if enabled)
/** @psalm-suppress TypeDoesNotContainType */
if ($migOpenBasedir === TRUE) {
    $openBasedirs = $migOpenBasedirExtraDirs;
    if (isset($configFile)) {
        array_push($openBasedirs, $configFile);
    }
    array_push($openBasedirs, $mig_config['albumdir']);
    array_push($openBasedirs, $mig_config['basedir'] . '/templates');
    ini_set('open_basedir', implode(PATH_SEPARATOR, $openBasedirs));
}

//for old compatibility: remove in mig 2.0:
/** @psalm-suppress TypeDoesNotContainType */
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
$mig_config['charset']                          = $migCharset;
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

function getHttpGetVariable($name, $default = NULL) {
    global $HTTP_GET_VARS;
    if (isset($_GET[$name])) {
        return $_GET[$name];
    } elseif (isset($HTTP_GET_VARS[$name])) {
        return $HTTP_GET_VARS[$name];
    }
    return $default;
}

function getHttpServerVariable($name, $default = NULL) {
    global $HTTP_SERVER_VARS;
    if (isset($_SERVER[$name])) {
        return $_SERVER[$name];
    } elseif (isset($HTTP_SERVER_VARS[$name])) {
        return $HTTP_SERVER_VARS[$name];
    }
    return $default;
}

function migRedirect($path)
{
    global $mig_config, $URI_SCHEME, $SERVER_NAME, $SERVER_PORT;
    $redirectTarget = $SERVER_NAME ? "$URI_SCHEME://$SERVER_NAME:$SERVER_PORT" : '';
    $relUrl = $mig_config['baseurl'] . $path;
    header("Location: $redirectTarget$relUrl");
    return 0;
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

// Jump has to come before currDir redirect to work
$jump = getHttpGetVariable('jump', FALSE);
// Is this a jump-tag URL?
if ($jump && isset($jumpMap[$jump])) {
    exit(migRedirect("?$jumpMap[$jump]"));
}
unset($jump);

// Jump-tag using PATH_INFO rather than "....?jump=x" URI
if (isset($PATH_INFO) && isset($jumpMap[$PATH_INFO])) {
    exit(migRedirect("?$jumpMap[$PATH_INFO]"));
}


// Get currDir.  If there isn't one, default to "."
$unchecked_currDir = getHttpGetVariable('currDir');
if (!$unchecked_currDir) {
    exit(migRedirect('?currDir=.'));
}

function is_in_album_dir($relative_path) {
    global $mig_config;
    $albumdir_abs = realpath($mig_config['albumdir']);
    $unchecked_abs_path = "$albumdir_abs/$relative_path";
    return string_starts_with(realpath($unchecked_abs_path), $albumdir_abs);
}

// Get rid of \'s if magic_quotes_gpc is turned on (causes problems).
if (_get_magic_quotes_gpc() == 1) {
    $unchecked_currDir = stripslashes($unchecked_currDir);
}

// Look at currDir from a security angle.  Don't let folks go outside
// the album directory base
if (strstr($unchecked_currDir, '..') || !preg_match($mig_config['currDirNameRegexpr'], $unchecked_currDir)) {
    exit('SECURITY VIOLATION - ABANDON SHIP');
}

// Try to validate currDir
//     Must be either "." (root) or,
//     must begin with "./" and dot or slash can't follow that
//     for at least two positions.
//
if ($unchecked_currDir != '.' && !preg_match('#^./[^/][^/]*#', $unchecked_currDir)) {
    exit('ERROR: $currDir is invalid. Exiting.');
}

// currDir may not end in / unless it is './' in its entirety
if ($unchecked_currDir != './' && preg_match('#/$#', $unchecked_currDir)) {
    exit('ERROR: $currDir has invalid format. Exiting.');
}

// double check: currDir must be in album dir (no directory traversal)
// This is an extra check in case someone sets $currDirNameRegexpr to a value that does not include directory traversal
// protection AND has open_basedir disable, quickly check if the absolute path of the image is inside the absolute path
// of our album dir.
// Note: this also requires the path to exist
if (!is_in_album_dir("$unchecked_currDir")) {
    exit("ERROR: Failed to load currDir " . migHtmlSpecialChars($unchecked_currDir));
}

// Strip URL encoding
$unchecked_currDir = rawurldecode($unchecked_currDir);
$unsafe_currDir = $unchecked_currDir; // upgrade to unsafe ("we checked it, but it's not safe for HTML/XSS purpose")
unset($unchecked_currDir); // remove unchecked GET parameter entirely

// Get image, if there is one.
$unchecked_image = getHttpGetVariable('image');

// Get rid of \'s if magic_quotes_gpc is turned on (causes problems).
if (_get_magic_quotes_gpc() == 1 && $unchecked_image) {
    $unchecked_image = stripslashes($unchecked_image);
}

// Look at $unchecked_image from a security angle.
// Don't let folks go outside the album directory base
// Don't let folks define ANY directory here
if (strstr($unchecked_image, '/') || strstr($unchecked_image, '\\') // image is never a full path
    || strstr($unchecked_image, '..') // basic directory traversal check, see below for more
    || !preg_match($mig_config['imageFilenameRegexpr'], $unchecked_image)) {
    exit('ERROR: $image is invalid.  Exiting.');
}

// double check: directory of image must be in album dir, no directory traversal (see comment for currDir above)
if ($unchecked_image && !is_in_album_dir($unsafe_currDir . '/' . dirname($unchecked_image))) {
    exit('ERROR: $image is not allowed. Exiting.');
}

$unsafe_image = $unchecked_image; // upgrade to unsafe ("we checked it, but it's not safe for HTML/XSS purpose")
unset($unchecked_image); // remove unchecked GET parameter entirely
$mig_config['unsafe_image'] = $unsafe_image;
$mig_config['enc_image'] = migHtmlSpecialChars($unsafe_image);

// check if the image exists...
if ($unsafe_image && !file_exists($mig_config['albumdir']."/$unsafe_currDir/".$unsafe_image)) {
    exit("ERROR: ".migHtmlSpecialChars($unsafe_currDir)."/".$mig_config['enc_image']." is invalid.  Exiting.");
}



// Get pageType.  If there isn't one, default to "folder"
$unsafe_pageType = getHttpGetVariable('pageType', 'folder');

// only allow one of the predefined values
$allowedTypes = array( "image" => 1, "folder" => 1, "large" => 1, "" => 1);

if(!isset($allowedTypes[$unsafe_pageType])) {
    exit('ERROR: $pageType is invalid.  Exiting.');
} else {
    $mig_config['pagetype'] = $unsafe_pageType; // pageType is now safe because it's from our allowed list
    unset($unsafe_pageType);
}
unset($allowedTypes);


// only allow digits for $startFrom
$mig_config['startfrom'] = intval(getHttpGetVariable('startFrom', 0));

// use language set specified in URL, if one was.
$unsafe_mig_dl = getHttpGetVariable('mig_dl', '');
// Only use it if we find it - otherwise fall back to default language
if (isset($mig_config['lang_lib'][$unsafe_mig_dl])) {
    $mig_language = $unsafe_mig_dl; // if the language was in our lang_lib, it is safe to use
    $mig_config['mig_dl'] = $mig_language;
} else {
    $mig_config['mig_dl'] = '';
}
unset($unsafe_mig_dl);


// Grab appropriate language from library
$mig_config['lang'] = $mig_config['lang_lib'][$mig_language];

// Backward compatibility with older config.php/mig.cfg versions
if (isset($maxColumns)) {
    $maxThumbColumns = $maxColumns;
}

// Turn off magic_quotes_runtime (causes trouble with some installations)
// (This method is deprecated as of 5.3, so only call it if the function exists)
if (function_exists('set_magic_quotes_runtime')) {
    /** @noinspection PhpDeprecationInspection */
    @set_magic_quotes_runtime(false);
}


//
// Handle any password authentication needs
//

$unsafe_workCopy = $unsafe_currDir;

while ($unsafe_workCopy) {

    if (isset($protect[$unsafe_workCopy])) {
        exit('password protection is not supported anymore');
    }

    // if $workCopy is already down to "." just nullify to end loop
    if ($unsafe_workCopy == '.') {
        $unsafe_workCopy = FALSE;
    } else {
        // parse $workCopy down one directory at a time
        // so we can check back all the way to "."
        $unsafe_workCopy = preg_replace('#/[^/]+$#', '', $unsafe_workCopy);
    }
}

// send Content-Type
header('Content-Type: '.$httpContentType);

// Where templates live
$mig_config['templatedir'] = $mig_config['basedir'] . '/templates';

// baseURL with the scriptname torn off the end
$baseHref = preg_replace('#/[^/]+$#', '', $mig_config['baseurl']);

// Location of image library (for instance, where icons are kept)
$mig_config['imagedir'] = $baseHref . '/images';

// Root where album images are living
$mig_config['albumurlroot'] = $baseHref . $albumRoot;
// NOTE: Sometimes Windows users have to set this manually, like:
// $mig_config['albumurlroot'] = '/mig/albums';

// Well, GIGO... set default to sane if someone screws up their
// config file

/** @psalm-suppress RedundantCondition,TypeDoesNotContainType */
if ($markerType != 'prefix' && $markerType != 'suffix' ) {
    $markerType = 'suffix';
}
$mig_config['markertype'] = $markerType;

/** @psalm-suppress TypeDoesNotContainType */
if (! $markerLabel) {
    $markerLabel = 'th';
}
$mig_config['markerlabel'] = $markerLabel;

/** @psalm-suppress TypeDoesNotContainType */
if (! $mig_config['foldersorttype']) {
    // Override folder sort if not present
    $mig_config['foldersorttype'] = $mig_config['sorttype'];
}

printPage($unsafe_currDir, $pathConvert, $unsafe_image);

?>
