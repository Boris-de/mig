<?php // $Id$

//
// MiG - A general purpose photo gallery management system.
//       http://mig.sourceforge.net/
// Copyright (C) 2000 Daniel M. Lowe	<dan@tangledhelix.com>
//
// LICENSE INFORMATION
// -------------------
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// You can find a copy of the GPL online at:
// http://www.gnu.org/copyleft/gpl.html
//
// ----------------------------------------------------------------------
//
// Please see the files in the docs/ subdirectory.
//
// Do not modify this file directly.  Please see the file docs/Install.txt
// for installation directions.  The code is written in such a way that
// all of your customization needs should be taken care of by the config
// file "mig.cfg".
//
// If you find that is not the case, and you hack in support for some
// feature you want to see in MiG, please contact me with a code diff
// and if I agree that it is useful to the general public, I will
// incorporate your code into the main code base for distribution.
//
// If I don't incorporate it I may very well offer it as "contributed"
// code that others can download if they wish to do so.
//


// Version number - Do not change
$version = '1.2.2';

// self-referential URL
if ($PHP_SELF)
    $baseURL = $PHP_SELF;
else
    $baseURL = $HTTP_SERVER_VARS['PHP_SELF'];

// base directory of installation
if ($PATH_TRANSLATED)
    $baseDir = dirname($PATH_TRANSLATED);
else
    $baseDir = dirname($HTTP_SERVER_VARS['PATH_TRANSLATED']);

$configFile = $baseDir . '/mig.cfg';	// Configuration file
$defaultConfigFile = $configFile . '.default';	// Default config file
		// (used if $configFile does not exist)

// Default settings (probably over-ridden by mig.cfg or mig.cfg.default)
$maxFolderColumns = 2;
$maxThumbColumns = 4;
$pageTitle = 'Photo Album';
$maintAddr = 'webmaster@mydomain.com';
$distURL = 'http://tangledhelix.com/software/mig/';
$markerType = 'suffix';
$markerLabel = 'th';
$phpNukeCompatible = FALSE;
$suppressImageInfo = FALSE;
$useThumbSubdir = FALSE;
$thumbSubdir = 'thumbs';
$noThumbs = FALSE;
$suppressAltTags = FALSE;
$mig_language = 'en';
$sortType = 'default';

// Fetch variables from the QUERY_STRING
if (!$currDir) {
    $currDir        = $HTTP_GET_VARS['currDir'];
    $image          = $HTTP_GET_VARS['image'];
    $pageType       = $HTTP_GET_VARS['pageType'];
}

if (!$jump)
    $jump           = $HTTP_GET_VARS['jump'];

// Set a current directory if one doesn't exist
// If there is one present, strip URL encoding from it
if ($currDir == '')
    $currDir = '.';
else
    $currDir = rawurldecode($currDir);

// Read configuration file
if (file_exists($configFile))
    $realConfig = $configFile;
else
    $realConfig = $defaultConfigFile;

include($realConfig);

// Change $baseDir for PHP-Nuke compatibility mode
if ($phpNukeCompatible)
    $baseDir .= '/mig';

// Make functions available for use
$funcsFile = $baseDir . '/funcs.php';
include($funcsFile);

// Load language file
$langFile = $baseDir . '/lang.php';
include($langFile);

// Backward compatibility with older mig.cfg versions
if ($maxColumns)
    $maxThumbColumns = $maxColumns;

// Get rid of \'s if magic_quotes_gpc is turned on.
if (get_magic_quotes_gpc() == 1) {
    $currDir = stripslashes($currDir);
    $image = stripslashes($image);
}

// Turn off magic_quotes_runtime
set_magic_quotes_runtime(0);

// Handle any password authentication needs

$workCopy = $currDir;       // temporary copy of $currDir

while ($workCopy) {

    if ($protect[$workCopy]) {
        // If there's not a username yet, fetch one by popping up a
        // login dialog box
        if (!isset($PHP_AUTH_USER)) {
            header('WWW-Authenticate: Basic realm="protected"');
            header('HTTP/1.0 401 Unauthorized');
            //print 'You must enter a valid username and password to enter';
            print $mig_messages[$mig_language]['must_auth'];
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
                //print 'You must enter a valid username and password to enter';
                print $mig_messages[$mig_language]['must_auth'];
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
        $workCopy = ereg_replace('/[^/]+$', '', $workCopy);
}

$albumDir = $baseDir . '/albums';	// Where albums live
// If you change the directory here also make sure to change $albumURLroot

$templateDir = $baseDir . '/templates';	// Where templates live

// $baseURL with the scriptname torn off
$baseHref = ereg_replace('/[^/]+$', '', $baseURL);

// Change $baseHref for PHP-Nuke compatibility mode
if ($phpNukeCompatible)
    $baseHref .= '/mig';

// Location of image library (for instance, where icons are kept)
$imageDir = $baseHref . '/images';

// Root where album images are living
$albumURLroot = $baseHref . '/albums';
// Sometimes Windows users have to set this manually, like:
// $albumURLroot = '/mig/albums';

// Well, GIGO... set default to sane if someone screws up their
// config file
if ($markerType != 'prefix' and $markerType != 'suffix')
    $markerType='suffix';

if (!$markerLabel)
    $markerLabel = 'th';

if (!$SERVER_NAME)
    $SERVER_NAME = $HTTP_SERVER_VARS['SERVER_NAME'];
    $PATH_INFO = $HTTP_SERVER_VARS['PATH_INFO'];

// Is this a jump URL?
if ($jump and $jumpMap[$jump] and $SERVER_NAME) {
    header("Location: http://$SERVER_NAME$baseURL?$jumpMap[$jump]");
    exit;
}

// Jump using PATH_INFO rather than ?jump=x
if ($PATH_INFO and $jumpMap[$PATH_INFO] and $SERVER_NAME) {
    header("Location: http://$SERVER_NAME$baseURL?$jumpMap[$PATH_INFO]");
    exit;
}

// Is this a phpNuke compatible site?
if ($phpNukeCompatible) {

    // Bail out if the root directory isn't set.
    if (! $phpNukeRoot) {
        print 'FATAL ERROR: phpNuke Root Directory is not set.';
        exit;
    }

    if (!isset($mainfile))
        include('mainfile.php');

    include('header.php');  // PHP Nuke surrounding framework

    // Table to nest MiG in, inside the PHPNuke framework
    print '<table width="100%" border="0" cellspacing="0" cellpadding="2"';
    print ' bgcolor="#000000"><tr><td>';
    print '<table width="100%" border="0" cellspacing="1" cellpadding="7"';
    print ' bgcolor="#FFFFFF"><tr><td>';
}

// Look at $currDir from a security angle.  Don't let folks go outside
// the album directory base
if (ereg('\.\.', $currDir)) {
    print 'SECURITY VIOLATION';
    exit;
}

// strip URL encoding here too
$image = rawurldecode($image);

// if $pageType is null, or "folder") generate a folder view

if ($pageType == 'folder' or $pageType == '') {

    // Determine which template to use depending on mode
    if ($phpNukeCompatible)
        $templateFile = 'mig_folder.php';
    else
        $templateFile = 'folder.html';

    // Generate some HTML to pass to the template printer

    // list of available folders
    $folderList = buildDirList($baseURL, $albumDir, $currDir, $imageDir,
                               $useThumbSubdir, $thumbSubdir,
                               $maxFolderColumns);
    // list of available images
    $imageList = buildImageList($baseURL, $baseDir, $albumDir, $currDir,
                                $albumURLroot, $maxThumbColumns, $folderList,
                                $markerType, $markerLabel, $suppressImageInfo,
                                $useThumbSubdir, $thumbSubdir, $noThumbs,
                                $thumbExt, $suppressAltTags, $mig_language,
                                $mig_messages, $sortType);
    // bulletin text, if any
    $bulletin = getBulletin($albumDir, $currDir);

    // Only frame the lists in table code when appropriate

    // no folders or images - print the "no contents" line
    if ($folderList == 'NULL' and $imageList == 'NULL') {
        //$folderList = 'No&nbsp;contents.';
        $folderList = $mig_messages[$mig_language]['no_contents'];
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
                              $homeLabel, $noThumbs, $mig_language,
                              $mig_messages);

    // build the "you are here" line
    $youAreHere = buildYouAreHere($baseURL, $currDir, '', $mig_language,
                                  $mig_messages);

    // parse the template file and print to stdout
    printTemplate($baseURL, $templateDir, $templateFile, $version, $maintAddr,
                  $folderList, $imageList, $backLink, '', '', '', '',
                  $pageTitle, '', '', '', $bulletin, $youAreHere, $distURL,
                  $albumDir);


// If $pageType is "image", show an image

} elseif ($pageType == 'image') {

    // Trick the back link into going to the right place by adding
    // a bogus directory at the end
    $backLink = buildBackLink($baseURL, "$currDir/blah", 'up', '', '',
                              $noThumbs, $mig_language, $mig_messages);

    // Get the "next image" and "previous image" links, and the current
    // position (#x of y)
    $Links = array();
    $Links = buildNextPrevLinks($baseURL, $albumDir, $currDir, $image,
                                $markerType, $markerLabel, $mig_language,
                                $mig_messages);
    $nextLink = $Links[0];
    $prevLink = $Links[1];
    $currPos = $Links[2];

    // Get image description
    $description  = getImageDescription($albumDir, $currDir, $image);
    $exifDescription = getExifDescription($albumDir, $currDir, $image);

    // If both descriptions are non-NULL, separate them with an HR
    if ($description and $exifDescription) {
        $description .= '<hr>';
        $description .= $exifDescription;
    }

    // If there's a description at all, frame it in a table.
    if ($description != '')
        $description = descriptionFrame($description);

    // Build the "you are here" line
    $youAreHere = buildYouAreHere($baseURL, $currDir, $image, $mig_language,
                                  $mig_messages);

    // Determine what template to use, based on what mode we are in
    if ($phpNukeCompatible)
        $templateFile = 'mig_image.php';
    else
        $templateFile = 'image.html';

    // newcurrdir is currdir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // Send it all to the template printer to dump to stdout
    printTemplate($baseURL, $templateDir, $templateFile, $version, $maintAddr,
                  '', '', $backLink, $albumURLroot, $image, $currDir,
                  $newCurrDir, $pageTitle, $prevLink, $nextLink, $currPos,
                  $description, $youAreHere, $distURL, $albumDir, '', '');
}

// If in PHPNuke mode, finish up the tables and such needed for PHPNuke
if ($phpNukeCompatible) {
    print '</table></center></td></tr></table></td></tr></table>';
    include('footer.php');
}

// That's all, folks

?>
