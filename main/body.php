
// URL to use to call myself again
if ($PHP_SELF)                                          // register_globals
    $baseURL = $PHP_SELF;
else                                                    // track_vars
    $baseURL = $HTTP_SERVER_VARS['PHP_SELF'];

// Base directory of installation
if ($PATH_TRANSLATED)                                   // register_globals
    $baseDir = dirname($PATH_TRANSLATED);
else                                                    // track_vars
    $baseDir = dirname($HTTP_SERVER_VARS['PATH_TRANSLATED']);

$configFile = $baseDir . '/config.php';   // Configuration file

// Get currDir.  If there isn't one, default to '.'
if (! $currDir && ! $HTTP_GET_VARS['currDir'])
    $currDir = '.';
elseif ($HTTP_GET_VARS['currDir'])
    $currDir = $HTTP_GET_VARS['currDir'];

// Strip URL encoding
$currDir = rawurldecode($currDir);

// Get image, if there is one.
if (! $image)
    $image = $HTTP_GET_VARS['image'];

// Get pageType.  If there isn't one, default to "folder"
if (! $pageType)
    $pageType = $HTTP_GET_VARS['pageType'];

if (! $pageType)
    $pageType = 'folder';

if (! $jump)
    $jump = $HTTP_GET_VARS['jump'];         // for track_vars

// Read configuration file
if (file_exists($configFile))
    include($configFile);

// Grab appropriate language from library
$mig_config['lang'] = $mig_config['lang_lib'][$mig_language];

// Backward compatibility with older config.php/mig.cfg versions
if ($maxColumns)
    $maxThumbColumns = $maxColumns;

// Get rid of \'s if magic_quotes_gpc is turned on (causes problems).
if (get_magic_quotes_gpc() == 1) {
    $currDir = stripslashes($currDir);
    if ($image) {
        $image = stripslashes($image);
    }
}

// Turn off magic_quotes_runtime (causes trouble with some installations)
set_magic_quotes_runtime(0);

//
// Handle any password authentication needs
//

$workCopy = $currDir;     // temporary copy of currDir

while ($workCopy) {

    if ($protect[$workCopy]) {

        // Try to get around the track_vars/register_globals problem
        if (! $PHP_AUTH_USER)
            $PHP_AUTH_USER = $HTTP_SERVER_VARS['PHP_AUTH_USER'];
        if (! $PHP_AUTH_PW)
            $PHP_AUTH_PW = $HTTP_SERVER_VARS['PHP_AUTH_PW'];

        // If there's not a username yet, fetch one by popping up a
        // login dialog box
        if (! $PHP_AUTH_USER) {
            header('WWW-Authenticate: Basic realm="protected"');
            header('HTTP/1.0 401 Unauthorized');
            print $mig_config['lang']['must_auth'];
            exit;

        } else {
            // Case #2: password/user are present but don't match up
            // with our known user base.  Reject the attempt.
            if ( crypt($PHP_AUTH_PW,
                       substr($protect[$workCopy][$PHP_AUTH_USER],0,2))
                 != $protect[$workCopy][$PHP_AUTH_USER] )
            {
                header('WWW-Authenticate: Basic realm="protected"');
                header('HTTP/1.0 401 Unauthorized');
                print $mig_config['lang']['must_auth'];
                exit;
            }
        }
        break;      // Since we had a match let's stop this loop
    }

    // if $workCopy is already down to '.' just nullify to end loop
    if ($workCopy == '.')
        $workCopy = FALSE;
    else
        // pare $workCopy down one directory at a time
        // so we can check back all the way to '.'
        $workCopy = ereg_replace('/[^/]+$', '', $workCopy);

}

$albumDir = $baseDir . '/albums';     // Where albums live
// If you change the directory here also make sure to change $albumURLroot

$templateDir = $baseDir . '/templates'; // Where templates live

// baseURL with the scriptname torn off the end
$baseHref = ereg_replace('/[^/]+$', '', $baseURL);

// Location of image library (for instance, where icons are kept)
$imageDir = $baseHref . '/images';

// Root where album images are living
$albumURLroot = $baseHref . '/albums';
// NOTE: Sometimes Windows users have to set this manually, like:
// $albumURLroot = '/mig/albums';

// Well, GIGO... set default to sane if someone screws up their
// config file
if ($markerType != 'prefix' and $markerType != 'suffix')
    $markerType='suffix';

if (! $markerLabel)
    $markerLabel = 'th';

// (Try to) get around the track_vars vs. register_globals problem
if (!$SERVER_NAME) {
    $SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
    $PATH_INFO = $HTTP_SERVER_VARS['PATH_INFO'];
}

// Is this a jump-tag URL?
if ($jump and $jumpMap[$jump] and $SERVER_NAME) {
    header("Location: http://$SERVER_NAME$baseURL?$jumpMap[$jump]");
    exit;
}

// Jump-tag using PATH_INFO rather than "....?jump=x" URI
if ($PATH_INFO and $jumpMap[$PATH_INFO] and $SERVER_NAME) {
    header("Location: http://$SERVER_NAME$baseURL?$jumpMap[$PATH_INFO]");
    exit;
}

// Look at currDir from a security angle.  Don't let folks go outside
// the album directory base
if (strstr($currDir, '..')) {
    print "SECURITY VIOLATION - ABANDON SHIP";
    exit;
}

// strip URL encoding here too
$image = rawurldecode($image);

// Fetch mig.cf information
list($hidden, $presort_dir, $presort_img, $desc, $bulletin, $ficons,
     $folderTemplate, $folderPageTitle, $folderFolderCols, $folderThumbCols,
     $folderMaintAddr)
  = parseMigCf("$albumDir/$currDir", $useThumbSubdir, $thumbSubdir);

// if pageType is "folder") generate a folder view

if ($pageType == 'folder') {

    // Determine which template to use
    if ($folderTemplate)
        $templateFile = $folderTemplate;
    else
        $templateFile = $templateDir . '/folder.html';

    // Determine page title to use
    if ($folderPageTitle)
        $pageTitle = $folderPageTitle;

    // Set per-folder $maintAddr if one was defined
    if ($folderMaintAddr)
        $maintAddr = $folderMaintAddr;

    // Determine columns to use
    if ($folderFolderCols)
        $maxFolderColumns = $folderFolderCols;

    if ($folderThumbCols)
        $maxThumbColumns = $folderThumbCols;

    // Generate some HTML to pass to the template printer

    // list of available folders
    $folderList = buildDirList($baseURL, $albumDir, $currDir, $imageDir,
                               $useThumbSubdir, $thumbSubdir,
                               $maxFolderColumns, $hidden, $presort_dir,
                               $viewFolderCount, $markerType, $markerLabel,
                               $ficons);
    // list of available images
    $imageList = buildImageList($baseURL, $baseDir, $albumDir, $currDir,
                                $albumURLroot, $maxThumbColumns, $folderList,
                                $markerType, $markerLabel, $suppressImageInfo,
                                $useThumbSubdir, $thumbSubdir, $noThumbs,
                                $thumbExt, $suppressAltTags, $sortType,
                                $hidden, $presort_img, $desc, $imagePopup,
                                $imagePopType, $commentFilePerImage);

    // Only frame the lists in table code when appropriate

    // no folders or images - print the "no contents" line
    if ($folderList == 'NULL' and $imageList == 'NULL') {
        $folderList = $mig_config['lang']['no_contents'];
        $folderList = folderFrame($folderList);
        $imageList = '';

    // images, no folders.  Frame the imagelist in a table
    } elseif ($folderList == 'NULL' and $imageList != 'NULL') {
        $folderList = '';
        $imageList = imageFrame($imageList);

    // folders but no images.  Frame the folderlist in a table
    } elseif ($imageList == 'NULL' and $folderList != 'NULL') {
        $imageList = '';
        $folderList = folderFrame($folderList);

    // We have folders and we have images, so frame both in tables.
    } else {
        $folderList = folderFrame($folderList);
        $imageList = imageFrame($imageList);
    }

    // We have a bulletin
    if ($bulletin != '')
        $bulletin = descriptionFrame($bulletin);

    // build the "back" link
    $backLink = buildBackLink($baseURL, $currDir, 'back', $homeLink,
                              $homeLabel, $noThumbs);

    // build the "you are here" line
    $youAreHere = buildYouAreHere($baseURL, $currDir, NULL);

    // newcurrdir is currdir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // parse the template file and print to stdout
    printTemplate($baseURL, $templateDir, $templateFile, $version, $maintAddr,
                  $folderList, $imageList, $backLink, NULL, NULL, NULL,
                  $newCurrDir, $pageTitle, NULL, NULL, NULL, $bulletin,
                  $youAreHere, $distURL, $albumDir);


// If pageType is "image", show an image

} elseif ($pageType == 'image') {

    // Set per-foler page title if one was defined
    if ($folderPageTitle)
        $pageTitle = $folderPageTitle;

    // Set per-folder maintAddr if one was defined
    if ($folderMaintAddr)
        $maintAddr = $folderMaintAddr;

    // Trick the back link into going to the right place by adding
    // a bogus directory at the end
    $backLink = buildBackLink($baseURL, "$currDir/blah", 'up', NULL, NULL,
                              $noThumbs);

    // Get the "next image" and "previous image" links, and the current
    // position (#x of y)
    $Links = array ();
    $Links = buildNextPrevLinks($baseURL, $albumDir, $currDir, $image,
                                $markerType, $markerLabel, $hidden,
                                $presort_img, $sortType);
    list($nextLink, $prevLink, $currPos) = $Links;

    // Get image description
    if ($commentFilePerImage)
        $description  = getImageDescFromFile($image, $albumDir, $currDir);
    else
        $description  = getImageDescription($image, $desc);

    $exifDescription = getExifDescription($albumDir, $currDir, $image,
                                          $exifFormatString);

    // If there's a description but no exifDescription, just make the
    // exifDescription the description
    if ($exifDescription and ! $description) {
        $description = $exifDescription;
        unset($exifDescription);
    }

    // If both descriptions are non-NULL, separate them with an <HR>
    if ($description and $exifDescription) {
        $description .= '<hr>';
        $description .= $exifDescription;
    }

    // If there's a description at all, frame it in a table.
    if ($description != '')
        $description = descriptionFrame($description);

    // Build the "you are here" line
    $youAreHere = buildYouAreHere($baseURL, $currDir, $image);

    // Which template to use.
    $templateFile = $templateDir . '/image.html';

    // newcurrdir is currdir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // Send it all to the template printer to dump to stdout
    printTemplate($baseURL, $templateDir, $templateFile, $version, $maintAddr,
                  NULL, NULL, $backLink, $albumURLroot, $image, $currDir,
                  $newCurrDir, $pageTitle, $prevLink, $nextLink, $currPos,
                  $description, $youAreHere, $distURL, $albumDir);
}

