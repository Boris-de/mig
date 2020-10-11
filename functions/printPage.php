<?php

function printPage($currDir, $pathConvert, $image)
{
    global $mig_config;

    // Fetch mig.cf information
    list($presort_dir, $presort_img, $desc, $short_desc, $bulletin,
        $folderIcons, $folderTemplate, $folderCols, $folderThumbCols, $folderMaintAddr)
        = parseMigCf($mig_config['albumdir'] . "/$currDir");

    // strip URL encoding here too
    $mig_config['image'] = rawurldecode($mig_config['image']);

    // if pageType is "folder") generate a folder view
    if ($mig_config['pagetype'] == 'folder') {

        // Generate some HTML to pass to the template printer

        // list of available folders
        $folderList = buildDirList($currDir, $folderCols, $presort_dir, $folderIcons);
        // list of available images
        $imageList = buildImageList($currDir, $folderThumbCols, $mig_config['maxThumbRows'],
            $presort_img, $desc, $short_desc);

        // Only frame the lists in table code when appropriate

        // Set style of table, either with text or thumbnails
        if ($mig_config['randomfolderthumbs']) {
            $folderTableClass = 'folderthumbs';
        } else {
            $folderTableClass = 'foldertext';
        }

        if ($folderList != '') {
            $folderList = buildTable($folderList, $folderTableClass, 'Folders Frame');
        } else if ($imageList == '') {
            // no folders and no images: add message
            $folderList = buildTable($mig_config['lang']['no_contents'], $folderTableClass, 'Folders Frame');
        }

        // We have a bulletin
        if ($bulletin != '') {
            $bulletin = buildTable('<center>' . $bulletin . '</center>', 'desc',
                'Bulletin Frame" width="60%'); //<--- kludge for now
        }

        // build the "back" link
        $backLink = buildBackLink($currDir, 'back');

        // build the "you are here" line
        $youAreHere = buildYouAreHere($currDir);

        // newcurrdir is currdir without the leading "./"
        $newCurrDir = getNewCurrDir($currDir);

        // parse the template file and print to stdout
        printTemplate($folderTemplate, $folderMaintAddr, $folderList, $imageList, $backLink, '',
            $newCurrDir, '', '', '', $bulletin,
            $youAreHere, $pathConvert, '', '', '', '');


    // If pageType is "image", show an image
    } elseif ($mig_config['pagetype'] == 'image') {

        // Trick back link into going to the right place by adding
        // a bogus directory at the end
        $backLink = buildBackLink("$currDir/blah", 'up');

        // Get the "next image" and "previous image" links, and the current
        // position (#x of y)
        list($nextLink, $prevLink, $currPos) = buildNextPrevLinks($currDir, $presort_img);

        // Get image description
        $description = _getImageDescription($mig_config, $currDir, $image, $desc, $short_desc);

        // Build the "you are here" line
        $youAreHere = buildYouAreHere($currDir);

        // Which template to use.
        $templateFile = $mig_config['templatedir'] . '/image.html';

        // newcurrdir is currdir without the leading "./"
        $newCurrDir = getNewCurrDir($currDir);

        $largeLink = '';
        $largeHrefStart = '';
        $largeHrefEnd = '';
        $largeLinkBorder = '';
        if ($mig_config['uselargeimages'] &&
            file_exists($mig_config['albumdir'] . "/$currDir/"
                . $mig_config['largesubdir']
                . '/' . $mig_config['image'])) {
            $largeLink = buildLargeLink($currDir);

            // Only build this link if we plan to use it
            if ($mig_config['largeLinkFromMedium']) {
                $largeHrefStart = buildLargeHrefStart($currDir);
                $largeHrefEnd = '</a>';
            }

            // Use a border?
            if (!$mig_config['largeLinkUseBorders']) {
                $largeLinkBorder = ' border="0"';
            }
        }

        // Send it all to the template printer to dump to stdout
        printTemplate($templateFile, $folderMaintAddr, '', '', $backLink,
            $currDir, $newCurrDir, $prevLink, $nextLink, $currPos,
            $description, $youAreHere, $pathConvert, $largeLink, $largeHrefStart, $largeHrefEnd,
            $largeLinkBorder);

    // If the pageType is "large", show a large image
    } elseif ($mig_config['pagetype'] == 'large') {

        // Trick the back link into going to the right place by adding
        // a bogus directory at the end
        $backLink = buildBackLink("$currDir/blah", 'up');

        // Get the "next image" and "previous image" links, and the current
        // position (#x of y)
        list($nextLink, $prevLink, $currPos) = buildNextPrevLinks($currDir, $presort_img);

        // Get image description
        $description = _getImageDescription($mig_config, $currDir, $image, $desc, $short_desc);

        // Build the "you are here" line
        $youAreHere = buildYouAreHere($currDir);

        // Which template to use
        $templateFile = $mig_config['templatedir'] . '/large.html';

        // newcurrdir is currdir without the leading "./"
        $newCurrDir = getNewCurrDir($currDir);

        // Send it all to the template printer to dump to stdout
        printTemplate($templateFile, $folderMaintAddr, '', '', $backLink,
            $currDir, $newCurrDir, $prevLink, $nextLink, $currPos,
            $description, $youAreHere, $pathConvert, '', '', '', '');
    }
}

function _getImageDescription($mig_config, $currDir, $image, $desc, $short_desc)
{
    if ($mig_config['commentfileperimage']) {
        list($x, $description) = getImageDescFromFile($currDir, $image);
        // If getImageDescFromFile() returned false, get the normal
        // comment if there is one.
        if (!$description) {
            list($x, $description) = getImageDescription($image, $desc, $short_desc);
        }
    } else {
        list($x, $description) = getImageDescription($image, $desc, $short_desc);
    }

    if (!$description) {
        $description = '';
    }

    $exifDescription = getExifDescription($currDir, $mig_config['exifFormatString']);

    // If there's a exifDescription but no description, just make the
    // exifDescription the description
    if ($exifDescription) {
        if ($description != '') {
            $description .= '<hr />';
        }
        $description .= $exifDescription;
    }
    return $description;
}
?>