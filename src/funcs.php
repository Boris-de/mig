<?php // $Revision$

//
// funcs.php - function library for MiG
//
// MiG - A general purpose photo gallery management system.
//       http://mig.sourceforge.net/
// Copyright (C) 2000-2001 Dan Lowe <dan@tangledhelix.com>
//
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
// Do not modify this file directly.  Please see the file docs/INSTALL
// for installation directions.  The code is written in such a way that
// all of your customization needs should be taken care of by the config
// file "mig.cfg".
//
// If you find that is not the case, and you hack in support for some
// feature you want to see in MiG, please contact me with a code diff
// and if I agree that it is useful to the general public, I will
// incorporate your code into the main code base.
//


// ----------------------------------------------------------------------
// parseMigCf - Parse a mig.cf file for sort and hidden blocks

function parseMigCf ( $directory, $useThumbSubdir, $thumbSubdir )
{

    // What file to parse
    $cfgfile = 'mig.cf';

    // Prototypes
    $hidden         = array ();
    $presort_dir    = array ();
    $presort_img    = array ();
    $desc           = array ();
    $ficons         = array ();

    // Hide thumbnail subdirectory if one is in use.
    if ($useThumbSubdir) {
        $hidden[$thumbSubdir] = TRUE;
    }

    if (file_exists("$directory/$cfgfile")) {
        $file = fopen("$directory/$cfgfile", 'r');
        $line = fgets($file, 4096);     // get first line

        while (! feof($file)) {

            // Parse <hidden> blocks
            if (eregi('^<hidden>', $line)) {
                $line = fgets($file, 4096);
                while (! eregi('^</hidden>', $line)) {
                    $line = trim($line);
                    $hidden[$line] = TRUE;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <sort> structure
            if (eregi('^<sort>', $line)) {
                $line = fgets($file, 4096);
                while (! eregi('^</sort>', $line)) {
                    $line = trim($line);
                    if (is_file("$directory/$line")) {
                        $presort_img[$line] = TRUE;
                    } elseif (is_dir("$directory/$line")) {
                        $presort_dir[$line] = TRUE;
                    }
                    $line = fgets($file, 4096);
                }
            }

            // Parse <bulletin> structure
            if (eregi('^<bulletin>', $line)) {
                $line = fgets($file, 4096);
                while (! eregi('^</bulletin>', $line)) {
                    $bulletin .= $line;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <comment> structure
            if (eregi('^<comment', $line)) {
                $commfilename = trim($line);
                $commfilename = str_replace('">', '', $commfilename);
                $commfilename = eregi_replace('^<comment "','',$commfilename);
                $line = fgets($file, 4096);
                while (! eregi('^</comment', $line)) {
                    $line = trim($line);
                    $mycomment .= "$line ";
                    $line = fgets($file, 4096);
                }
                $desc[$commfilename] = $mycomment;
                $commfilename = '';
                $mycomment = '';
            }

            // Parse FolderIcon lines
            if (eregi('^foldericon ', $line)) {
                $x = trim($line);
                list($y, $folder, $icon) = explode(' ', $x);
                $ficons[$folder] = $icon;
            }

            // Parse FolderTemplate lines
            if (eregi('^foldertemplate ', $line)) {
                $x = trim($line);
                list($y, $template) = explode(' ', $x);
            }

            // Parse PageTitle lines
            if (eregi('^pagetitle ', $line)) {
                $x = trim($line);
                $pagetitle = eregi_replace('^pagetitle ', '', $x);
            }

            // Parse MaxFolderColumns lines
            if (eregi('^maxfoldercolumns ', $line)) {
                $x = trim($line);
                list($y, $fcols) = explode(' ', $x);
            }

            // Parse MaxThumbColumns lines
            if (eregi('^maxthumbcolumns ', $line)) {
                $x = trim($line);
                list($y, $tcols) = explode(' ', $x);
            }

            // Get next line
            $line = fgets($file, 4096);

        } // end of main while() loop

        fclose($file);
    }

    $retval = array ($hidden, $presort_dir, $presort_img, $desc,
                     $bulletin, $ficons, $template, $pagetitle,
                     $fcols, $tcols);
    return $retval;

}   //  -- End of parseMigCf()



// ----------------------------------------------------------------------
// printTemplate() - prints HTML page from a template file

function printTemplate ( $baseURL, $templateDir, $templateFile, $version,
                         $maintAddr, $folderList, $imageList, $backLink,
                         $albumURLroot, $image, $currDir, $newCurrDir,
                         $pageTitle, $prevLink, $nextLink, $currPos,
                         $description, $youAreHere, $distURL, $albumDir,
                         $server, $useVirtual )
{

    if (! ereg('^/', $templateFile)) {
        $templateFile = $albumDir . '/' . $newCurrDir . '/' . $templateFile;
    }

    // Panic if the template file doesn't exist.
    if (! file_exists($templateFile)) {
        print "ERROR: $templateFile does not exist!";
        exit;
    }

    $file = fopen($templateFile,'r');    // Open template file
    $line = fgets($file, 4096);                         // Get first line

    while (! feof($file)) {             // Loop until EOF

        // Look for include directives and process them
        if (ereg('^#include', $line)) {
            $orig_line = $line;
            $line = trim($line);
            $line = str_replace('#include "', '', $line);
            $line = str_replace('";', '', $line);
            if (strstr($line, '/')) {
                $line = '<!-- ERROR: #include directive failed.'
                      . ' Path included a "/" character, indicating'
                      . ' an absolute or relative path.  All included'
                      . ' files must be located in the templates/'
                      . ' subdirectory. Directive was:'
                      . "\n     $orig_line\n-->\n";
                print $line;
            } else {
                $incl_file = $line;
                if (file_exists("$templateDir/$incl_file")) {

                    // Is this a PHP file?
//                    if (eregi('.php3?',  $incl_file)) {
//                        // include as php
//                        include("$templateDir/$incl_file");
//
//                    } else {        // Not PHP, either CGI or just text
//
//                        // virtual() only works for Apache
//                        if (ereg('^Apache', $server) and $useVirtual) { 
//                            // virtual() doesn't like absolute paths,
//                            // apparently, so just pass it a relative one.
//                            $tmplDir = ereg_replace("^.*/", "", $templateDir);
//                            virtual("$tmplDir/$incl_file");
//                        } else {
//                            // readfile() just spits a file to stdout
//                            readfile("$templateDir/$incl_file");
//                        }
//                    }

                    if (function_exists('virtual')) {
                        // virtual() doesn't like absolute paths,
                        // apparently, so just pass it a relative one.
                        $tmplDir = ereg_replace("^.*/", "", $templateDir);
                        virtual("$tmplDir/$incl_file");
                    } else {
                        include("$templateDir/$incl_file");
                    }

                } else {
                    // If the file doesn't exist, complain.
                    $line = '<!-- ERROR: #include directive failed.'
                          . ' Named file ' . $incl_file
                          . ' does not exist.  Directive was:'
                          . "\n    $orig_line\n-->\n";
                    print $line;
                }
            }

        } else {

            // Make sure this is URL encoded
            $encodedImageURL = migURLencode($image);

            if ($image) {
                // Get image pixel size for <IMG> element
                $imageProps = GetImageSize("$albumDir/$currDir/$image");
                $imageSize = $imageProps[3];
            }

            // List of valid tags
            $replacement_list = array (
                'baseURL', 'maintAddr', 'version', 'folderList',
                'imageList', 'backLink', 'currDir', 'newCurrDir',
                'image', 'albumURLroot', 'pageTitle', 'nextLink',
                'prevLink', 'currPos', 'description', 'youAreHere',
                'distURL', 'encodedImageURL', 'imageSize'
            );

            // Do substitution for various variables
            while (list($key,$val) = each($replacement_list)) {
                $line = str_replace("%%$val%%", $$val, $line);
            }

            print $line;                // Print resulting line
        }
        $line = fgets($file, 4096);     // Grab another line
    }

    fclose($file);
    return TRUE;

}    // -- End of printTemplate()



// ----------------------------------------------------------------------
// buildDirList() - creates list of directories available

function buildDirList ( $baseURL, $albumDir, $currDir, $imageDir,
                        $useThumbSubdir, $thumbSubdir, $maxColumns,
                        $hidden, $presorted, $viewFolderCount,
                        $markerType, $markerLabel, $ficons )
{

    $oldCurrDir = $currDir;         // Stash this to build full path with

    // Create a URL-encoded version of $currDir
    $enc_currdir = $currDir;
    $currDir = rawurldecode($enc_currdir);

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    $directories = array ();                    // prototypes
    $counts = array ();

    if ($viewFolderCount) {
        while(list($file,$x) = each($presorted)) {
            $folder = "$albumDir/$currDir/$file";
            $counts[$file] = getNumberOfImages($folder,
                                $useThumbSubdir, $markerType,
                                $markerLabel);
        }
        reset($presorted);
    }

    while ($file = readdir($dir)) {

        // Ignore . and .. and make sure it's a directory
        if ($file != '.' and $file != '..'
            and is_dir("$albumDir/$currDir/$file")) {

            // Ignore anything that's hidden or was already sorted.
            if (!$hidden[$file] and !$presorted[$file]) {

                // Stash file in an array
                $directories[$file] = TRUE;

                // Get a count of the images it contains, if
                // desired.
                if ($viewFolderCount) {
                    $folder = "$albumDir/$currDir/$file";
                    $counts[$file] = getNumberOfImages($folder,
                                        $useThumbSubdir, $markerType,
                                        $markerLabel);
                }
            }
        }
    }

    ksort($directories);    // sort so we can yank them in sorted order
    reset($directories);    // reset array pointer to beginning

    // snatch each element from $directories and shove it on the end of
    // $presorted
    while (list($file,$junk) = each($directories)) {
        $presorted[$file] = TRUE;
    }

    reset($presorted);          // reset array pointer

    // Track columns
    $row = 0;
    $col = 0;
    $maxColumns--;  // Tricks $maxColumns into working since it
                    // really starts at 0, not 1

    while (list($file,$junk) = each($presorted)) {

        // Start a new row if appropriate
        if ($col == 0) {
            $directoryList .= '<tr>';
        }

        // Surmise the full path to work with
        $newCurrDir = $oldCurrDir . '/' . $file;

        // URL-encode the directory name in case it contains spaces
        // or other weirdness.
        $enc_file = migURLencode($newCurrDir);

        // Build the link itself for re-use below
        $linkURL = '<a href="' . $baseURL
                 . '?pageType=folder&currDir=' . $enc_file . '">';

        // Reword $file so it doesn't allow wrapping of the label
        // (fixes odd formatting bug in MSIE).
        // Also, render _ as a space.
        $nbspfile = $file;
        $nbspfile = str_replace(' ', '&nbsp;', $nbspfile);
        $nbspfile = str_replace('_', '&nbsp;', $nbspfile);

        // Build the full link (icon plus folder name) and tack it on
        // the end of the list.
        $directoryList .= '<td class="folder">' . $linkURL . '<img src="'
                       . $imageDir . '/';
        if ($ficons[$file]) {
            $directoryList .= $ficons[$file];
        } else {
            $directoryList .= 'folder.gif';
        }
        $directoryList .= '" border="0"></a>&nbsp;'
                       . $linkURL . '<font size="-1">' . $nbspfile
                       . '</font></a>';
        if ($viewFolderCount and $counts[$file] > 0) {
            $directoryList .= ' (' . $counts[$file] . ')';
        }
        $directoryList .= '</td>';

        // Keep track of what row/column we're on
        if ($col == $maxColumns) {
            $directoryList .= '</tr>';
            $row++;
            $col = 0;
        } else {
            $col++;
        }
    }

    closedir($dir); 

    // If there aren't any subfolders to look at, then just say so.
    if ($directoryList == '') {
        return 'NULL';

    } elseif (!eregi('</tr>$', $directoryList)) {

        // Stick a </tr> on the end if it isn't there already
        $directoryList .= '</tr>';
    }

    return $directoryList;

} // -- End of buildDirList()



// ----------------------------------------------------------------------
// buildImageList() - creates a list of images available

function buildImageList( $baseURL, $baseDir, $albumDir, $currDir,
                         $albumURLroot, $maxColumns, $directoryList,
                         $markerType, $markerLabel, $suppressImageInfo,
                         $useThumbSubdir, $thumbSubdir, $noThumbs,
                         $thumbExt, $suppressAltTags, $language,
                         $mig_messages, $sortType, $hidden, $presorted,
                         $description, $imagePopup, $imagePopType,
                         $commentFilePerImage )
{

    $dir = opendir("$albumDir/$currDir");       // Open directory handle

    $row = 0;               // Counters for the table formatting
    $col = 0;

    $maxColumns--;          // Tricks maxColumns into working since it
                            // really starts at 0, not 1.

    // prototype the arrays
    $imagefiles     = array ();
    $filedates      = array ();

    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$useThumbSubdir) {  // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == 'suffix'
                and eregi("_$markerLabel\.(gif|jpg|png|jpeg|jpe)$", $file)) {
                    continue;
            }

            if ($markerType == 'prefix' and ereg("^$markerLabel\_", $file)) {
                continue;
            }

        }

        // We'll look at this one only if it's a file, it's not hidden,
        // and it matches our list of approved extensions
        $ext = getFileExtension($file);
        if (is_file("$albumDir/$currDir/$file") and !$hidden[$file]
                        and !$presorted[$file]
                        and eregi('^(jpg|gif|png|jpeg|jpe)$', $ext))
        {
            // Stash file in an array
            $imagefiles[$file] = TRUE;
            // and stash a timestamp as well if needed
            if (ereg("bydate.*", $sortType)) {
                $timestamp = filemtime("$albumDir/$currDir/$file");
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    ksort($imagefiles); // sort, so we get a sorted list to stuff onto the
                        // end of $presorted

    reset($imagefiles); // reset array pointer

    if ($sortType == "bydate-ascend") {
        ksort($filedates);
        reset($filedates);

    } elseif ($sortType == "bydate-descend") {
        krsort($filedates);
        reset($filedates);
    }

    // Join the two sorted lists together into a single list
    if (ereg("bydate.*", $sortType)) {
        while(list($junk,$file) = each($filedates)) {
            $presorted[$file] = TRUE;
        }

    } else {
        while (list($file,$junk) = each($imagefiles)) {
            $presorted[$file] = TRUE;
        }
    }

    reset($presorted);          // reset array pointer

    while (list($file,$junk) = each($presorted)) {

        // Only look at valid image types
        $ext = getFileExtension($file);
        if (eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {

            // If this is a new row, start a new <TR>
            if ($col == 0) {
                $imageList .= '<tr>';
            }

            $fname = getFileName($file);
            $img = buildImageURL($baseURL, $baseDir, $albumDir, $currDir,
                                 $albumURLroot, $fname, $ext, $markerType,
                                 $markerLabel, $suppressImageInfo,
                                 $useThumbSubdir, $thumbSubdir, $noThumbs,
                                 $thumbExt, $suppressAltTags, $language,
                                 $mig_messages, $description, $imagePopup,
                                 $imagePopType, $commentFilePerImage);
            $imageList .= $img;

            // Keep track of what row and column we are on
            if ($col == $maxColumns) {
                $imageList .= '</tr>';
                $row++;
                $col = 0;
            } else {
                $col++;
            }
        }
    }

    closedir($dir);

    // If there aren't any images to work with, just say so.
    if ($imageList == '') {
        $imageList = 'NULL';
    } elseif (!eregi('</tr>$', $imageList)) {
        // Stick a </tr> on the end if it isn't there already.
        $imageList .= '</tr>';
    }

    return $imageList;

}   // -- End of buildImageList()



// ----------------------------------------------------------------------
// buildBackLink() - spits out a "back one section" link

function buildBackLink( $baseURL, $currDir, $type, $homeLink, $homeLabel,
                        $noThumbs, $language, $mig_messages )
{

    // $type notes whether we want a "back" link or "up one level" link.
    if ($type == 'back' or $noThumbs) {
        //$label = 'up&nbsp;one&nbsp;level';
        $label = $mig_messages[$language]['up_one'];
    } elseif ($type == 'up') {
        //$label = 'back&nbsp;to&nbsp;thumbnail&nbsp;view';
        $label = $mig_messages[$language]['thumbview'];
    }

    // don't send a link back if we're a the root of the tree
    if ($currDir == '.') {
        if ($homeLink != '') {

            if ($homeLabel == '') {
                $homeLabel = $homeLink;
            } else {
                // Get rid of spaces due to silly formatting in MSIE
                $homeLabel = str_replace(' ', '&nbsp;', $homeLabel);
            }

            // Build a link to the "home" page
            $retval  = '<font size="-1">[&nbsp;<a href="'
                     . $homeLink
                     . '">'
                     . $mig_messages[$language]['backhome']
                     . '&nbsp;'
                     . $homeLabel
                     . '</a>&nbsp;]</font><br><br>';
        } else {
            $retval = '<br>';
        }
        return $retval;
    }

    // Trim off the last directory, so we go "back" one.
    $junk = ereg_replace('/[^/]+$', '', $currDir);
    $newCurrDir = migURLencode($junk);

    $retval = '<font size="-1">[&nbsp;<a href="'
            . $baseURL . '?currDir=' . $newCurrDir . '">' . $label
            . '</a>&nbsp;]</font><br><br>';

    return $retval;

}   // -- End of buildBackLink()



// ----------------------------------------------------------------------
// buildImageURL() -- spit out HTML for a particular image

function buildImageURL( $baseURL, $baseDir, $albumDir, $currDir,
                        $albumURLroot, $fname, $ext, $markerType,
                        $markerLabel, $suppressImageInfo, $useThumbSubdir,
                        $thumbSubdir, $noThumbs, $thumbExt, $suppressAltTags,
                        $language, $mig_messages, $description, $imagePopup,
                        $imagePopType, $commentFilePerImage )
{

    // newCurrDir is currDir without leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // URL-encode currDir, keeping an old copy too
    $oldCurrDir = $currDir;
    $currDir = migURLencode($currDir);

    // URL-encoded the filename
    $newFname = rawurlencode($fname);

    // Only show a thumbnail if one exists.  Otherwise use a default
    // "generic" thumbnail image.

    if ($useThumbSubdir) {

        if ($thumbExt) {
            $thumbFile = "$albumDir/$oldCurrDir/$thumbSubdir/$fname.$thumbExt";
        } else {
            $thumbFile = "$albumDir/$oldCurrDir/$thumbSubdir/$fname.$ext";
        }

    } else {

        if ($markerType == 'prefix') {
            $thumbFile  = "$albumDir/$oldCurrDir/$markerLabel";

            if ($thumbExt) {
                $thumbFile .= "_$fname.$thumbExt";
            } else {
                $thumbFile .= "_$fname.$ext";
            }
        }

        if ($markerType == 'suffix') {
            $thumbFile  = "$albumDir/$oldCurrDir/$fname";
            if ($thumbExt) {
                $thumbFile .= "_$markerLabel.$thumbExt";
            } else {
                $thumbFile .= "_$markerLabel.$ext";
            }
        }
    }

    if (file_exists($thumbFile)) {
        if ($useThumbSubdir) {
            $thumbImage  = "$albumURLroot/$currDir/$thumbSubdir";
            if ($thumbExt) {
                $thumbImage .= "/$fname.$thumbExt";
            } else {
                $thumbImage .= "/$fname.$ext";
            }

        } else {

            if ($markerType == 'prefix') {
                $thumbImage  = "$albumURLroot/$currDir/$markerLabel";
                if ($thumbExt) {
                    $thumbImage .= "_$fname.$thumbExt";
                } else {
                    $thumbImage .= "_$fname.$ext";
                }
            }

            if ($markerType == 'suffix') {
                $thumbImage  = "$albumURLroot/$currDir/$fname";
                if ($thumbExt) {
                    $thumbImage .= "_$markerLabel.$thumbExt";
                } else {
                    $thumbImage .= "_$markerLabel.$ext";
                }
            }
        }
        $thumbImage = migURLencode($thumbImage);
    } else {
        $newRoot = ereg_replace('/[^/]+$', '', $baseURL);
        $thumbImage = $newRoot . '/images/no_thumb.gif';
    }

    // Get description, if any
    if ($commentFilePerImage) {
        $alt_desc = getImageDescFromFile("$fname.$ext", $albumDir, $currDir);
    } else {
        $alt_desc = getImageDescription("$fname.$ext", $description);
    }
    $alt_desc = strip_tags($alt_desc);

    // if both are present, separate with "--"
    //if ($alt_desc and $alt_exif) {
    //    $alt_desc .= " -- $alt_exif";
    //}

    // Figure out the image's size (in bytes and pixels) for display
    $imageFile = "$albumDir/$oldCurrDir/$fname.$ext";

    // Figure out the pixels
    $imageProps = GetImageSize($imageFile);
    $imageWidth = $imageProps[0];
    $imageHeight = $imageProps[1];

    // Figure out the bytes
    $imageSize = filesize($imageFile);
    if ($imageSize > 1048576) {
        $imageSize = sprintf('%01.1f', $imageSize / 1024 / 1024) . 'MB';
    } elseif ($imageSize > 1024) {
        $imageSize = sprintf('%01.1f', $imageSize / 1024) . 'KB';
    } else {
        $imageSize = $imageSize . $mig_messages[$language]['bytes'];
    }

    // Figure out thumbnail geometry
    $thumbHTML = '';
    if (file_exists($thumbFile)) {
        $thumbProps = GetImageSize($thumbFile);
        $thumbHTML = $thumbProps[3];
    }

    // beginning of the table cell
    $url = '<td class="image"><a';
    if (!$suppressAltTags) {
        $url .= ' title="' . $alt_desc . '"';
    }
    $url .= ' href="';

    // set up the image pop-up if appropriate to do so
    if ($imagePopup) {
        $popup_width = $imageWidth + 30;
        $popup_height = $imageHeight + 150;
        $url .= '#" onClick="window.open(\'';
    }

    $url .= $baseURL . '?currDir='
         . $currDir . '&pageType=image&image=' . $newFname
         . '.' . $ext;

    if ($imagePopup) {
        $url .= "','";
        if ($imagePopType == 'reuse') {
            $url .= 'mig_window_11190874';
        } else {
            $url .= 'mig_window_' . time() . '_' . $newFname;
        }
        $url .= "','width=$popup_width,height=$popup_height,"
              . "resizable=yes,scrollbars=1');";
    }

    $url .= '">';

    // If $noThumbs is true, just print the image filename rather
    // than the <IMG> tag pointing to a thumbnail.
    if ($noThumbs) {
        $url .= "$newFname.$ext";
    } else {
        $url .= '<img src="' . $thumbImage . '"';
            // Only print the ALT tag if it's wanted.
            if (! $suppressAltTags) {
                $url .= ' alt="' . $alt_desc . '"';
            }
        $url .= ' border="0" ' . $thumbHTML . '>';
    }

    $url .= '</a>';     // End the <A> element

    // If $suppressImageInfo is FALSE, show the image info
    if (!$suppressImageInfo) {
        $url .= '<br><font size="-1">';
        if (!$noThumbs) {
            $url .= $fname . '.' . $ext . '<br>';
        }
        $url .= '(' . $imageWidth . 'x' . $imageHeight . ', '
             . $imageSize . ')</font>';
    }

    $url .= '</td>';        // Close table cell
    return $url;

}   // -- End of buildImageURL()



// ----------------------------------------------------------------------
// buildNextPrevLinks() -- Build a link to the "next" and "previous"
// images.

function buildNextPrevLinks( $baseURL, $albumDir, $currDir, $image,
                             $markerType, $markerLabel, $language,
                             $mig_messages, $hidden, $presorted, $sortType )
{

    // newCurrDir is currDir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    $dir = opendir("$albumDir/$currDir");// Open directory handle

    // Gather all files into an array
    $fileList = array ();
    while ($file = readdir($dir)) {

        // Ignore thumbnails
        if ($markerType == 'prefix' and ereg("^$markerLabel\_", $file)) {
            continue;
        }
        if ($markerType == 'suffix'
            and eregi("_$markerLabel\.(gif|jpg|png|jpeg|jpe)$", $file)) {
                continue;
        }

        // Only look at valid image formats
        if (!eregi('\.(gif|jpg|png|jpeg|jpe)$', $file)) {
            continue; 
        }
        // Ignore the hidden images
        if ($hidden[$file]) {
            continue;
        }
        // Make sure this is a file, not a directory.
        // and make sure it isn't presorted
        if (is_file("$albumDir/$currDir/$file") and ! $presorted[$file]) {
            $fileList[$file] = TRUE;
            // Store a date, too, if needed
            if (ereg("bydate.*", $sortType)) {
                $timestamp = filemtime("$albumDir/$currDir/$file");
                $filedates["$timestamp-$file"] = $file;
            }
        }
    }

    closedir($dir); 

    ksort($fileList);       // sort, so we see sorted results
    reset($fileList);       // reset array pointer

    if ($sortType == "bydate-ascend") {
        ksort($filedates);
        reset($filedates);

    } elseif ($sortType == "bydate-descend") {
        krsort($filedates);
        reset($filedates);
    }

    // Generated final sorted list
    if (ereg("bydate.*", $sortType)) {
        // since $filedates is sorted by date, and date is
        // the key, the key is pointless to put in the list now.
        // so we store the value, not the key, in $presorted
        while (list($junk,$file) = each($filedates)) {
            $presorted[$file] = TRUE;
        }

    } else {
        // however, here we have real data in the key, so we push
        // the key, not the value, into $presorted.
        while (list($file,$junk) = each($fileList)) {
            $presorted[$file] = TRUE;
        }
    }

    reset($presorted);      // reset array pointer

    // Gather all files into an array

    $i = 1;                 // iteration counter, etc

    // Yes, position 0 is garbage.  Makes the math easier later.
    $fList = array ( 'blah' ); 

    while (list($file, $junk) = each($presorted)) {
    
        // If "this" is the one we're looking for, mark it as such.
        if ($file == $image) {
            $ThisImagePos = $i;
        }
        $fList[$i] = $file;     // Stash filename in the array
        $i++;                   // increment the counter, of course.
    } 
    reset($fList);

    $i--;                       // Get rid of the last increment...

    // Next is one more than $ThisImagePos.  Test if that has a value
    // and if it does, consider it "next".
    if ($fList[$ThisImagePos+1]) {
        $next = migURLencode($fList[$ThisImagePos+1]);
    } else {
        $next = 'NA';
    }

    // Previous must always be one less than the current index.  If
    // that has a value, that is.  Unless the current index is "1" in
    // which case we know there is no previous.
    
    if ($ThisImagePos == 1) {
        $prev = 'NA';
    } elseif ($fList[$ThisImagePos-1]) {
        $prev = migURLencode($fList[$ThisImagePos-1]); 
    }

    // URL-encode currDir
    $currDir = migURLencode($currDir);

    // newCurrDir is currDir without the leading './'
    $newCurrDir = getNewCurrDir($currDir);

    // If there is no previous image, show a greyed-out link
    if ($prev == 'NA') {
        $pLink = '<font size="-1">[&nbsp;<font color="#999999">'
               . $mig_messages[$language]['previmage']
               . '</font>&nbsp;]</font>';

    // else show a real link
    } else {
        $pLink = '<font size="-1">[&nbsp;<a href="' . $baseURL
               . '?pageType=image&currDir=' . $currDir . '&image='
               . $prev . '">' . $mig_messages[$language]['previmage']
               . '</a>&nbsp;]</font>';
    }

    // If there is no next image, show a greyed-out link
    if ($next == 'NA') {
        $nLink = '<font size="-1">[&nbsp;<font color="#999999">'
               . $mig_messages[$language]['nextimage']
               . '</font>&nbsp;]</font>';
    // else show a real link
    } else {
        $nLink = '<font size="-1">[&nbsp;<a href="' . $baseURL
               . '?pageType=image&currDir=' . $currDir . '&image='
               . $next . '">' . $mig_messages[$language]['nextimage']
               . '</a>&nbsp;]</font>';
    }

    // Current position in the list
    $currPos = '#' . $ThisImagePos . '&nbsp;of&nbsp;' . $i;

    $retval = array( $nLink, $pLink, $currPos );
    return $retval;

}   // -- End of buildNextPrevLinks()



// ----------------------------------------------------------------------
// buildYouAreHere() - build the "You are here" line for the top
// of each page

function buildYouAreHere( $baseURL, $currDir, $image, $language,
                          $mig_messages )
{

    // Use $workingCopy so we don't trash value of $currDir
    $workingCopy = $currDir;

    // Loop until we get down to just the '.'
    while ($workingCopy != '.') {

        // $label is the "last" thing in the path. Strip up to that
        $label = ereg_replace('^.*/', '', $workingCopy);
        // Render underscores as spaces and turn spaces into &nbsp;
        $label = str_replace('_', '&nbsp;', $label);
        $label = str_replace(' ', '&nbsp;', $label);

        // Get a URL-encoded copy of $workingCopy
        $encodedCopy = migURLencode($workingCopy);

        if ($image == '' and $workingCopy == $currDir) {
            $url = '&nbsp;:&nbsp;<b>' . $label . '</b>';
        } else {
            $url = '&nbsp;:&nbsp;<a href="' . $baseURL . '?currDir='
                 . $encodedCopy . '">' . $label . '</a>';
        }

        // Strip the last piece off of $workingCopy to go to next loop
        $workingCopy = ereg_replace('/[^/]+$', '', $workingCopy);

        // Build up the final path over each loop iteration
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If we're down to '.' as our currDir then this is 'Main'
    if ($currDir == '.') {
        $url = '<b>' . $mig_messages[$language]['main'] . '</b>';
        $x = $hereString;
        $hereString = $url . $x;

    // Or if we're not, then Main should be a link instead of just text
    } else {
        $url = '<a href="' . $baseURL . '?currDir=' . $workingCopy
             . '">' . $mig_messages[$language]['main'] . '</a>';
        $x = $hereString;
        $hereString = $url . $x;
    }

    // If there's an image, tack it onto the end of the hereString
    if ($image != '') {
        $hereString .= '&nbsp;:&nbsp;<b>' . $image . '</b>';
    }

    $x = $hereString;
    $hereString = '<font size="-1">' . $x . '</font>';
    return $hereString;

}   // -- End of buildYouAreHere()



// ----------------------------------------------------------------------
// getFileExtension() - figure out a file's extension and return it.

function getFileExtension( $file )
{
    // Strip off the extension part of the filename
    $ext = ereg_replace('^.*\.', '', $file);

    return $ext;

}   // -- End of getFileExtension()



// ----------------------------------------------------------------------
// getFileName() - figure out a file's name sans extension.

function getFileName( $file )
{
    // Strip off the non-extension part of the filename
    $fname = ereg_replace('\.[^\.]+$', '', $file);

    return $fname;

}   // -- End of getFileName()



// ----------------------------------------------------------------------
// getImageDescription() - Fetches an image description from the
// comments file (mig.cf)

function getImageDescription( $image, $description )
{
    $imageDesc = '';
    if ($description[$image]) {
        $imageDesc = $description[$image];
    }
    return $imageDesc;

}   // -- End of getImageDescription()



// ----------------------------------------------------------------------
// getImageDescFromFile() - Fetches an image description from a
// per-image comment file (used if $commentFilePerImage is TRUE)

function getImageDescFromFile( $image, $albumDir, $currDir )
{
    $imageDesc = '';
    $fname = getFileName($image);

    if (file_exists("$albumDir/$currDir/$fname.txt")) {

        $file = fopen("$albumDir/$currDir/$fname.txt", 'r');
        $line = fgets($file, 4096);     // get first line

        while (!feof($file)) {
            $line = trim($line);
            $imageDesc .= "$line ";
            $line = fgets($file, 4096); // get next line
        }

        fclose($file);
    }

    return $imageDesc;

}   // -- End of getImageDescFromFile();



// ----------------------------------------------------------------------
// getExifDescription() - Fetches a comment if available from the
// Exif comments file (exif.inf) as well as fetching EXIF data

function getExifDescription( $albumDir, $currDir, $image, $viewCamInfo,
                             $viewDateInfo)
{
    // Use global language settings
    global $mig_messages;
    global $mig_language;

    $desc = array ();
    $model = array ();
    $shutter = array ();
    $aperture = array ();
    $foclen = array ();
    $flash = array ();
    $iso = array ();
    $timestamp = array ();

    if (file_exists("$albumDir/$currDir/exif.inf")) {

        $file = fopen("$albumDir/$currDir/exif.inf", 'r');
        $line = fgets($file, 4096);     // get first line
        while (!feof($file)) {

            if (ereg('^File name    : ', $line)) {
                $fname = ereg_replace('^File name    : ', '', $line);
                $fname = chop($fname);

            } elseif (ereg('^Comment      : ', $line)) {
                $comment = ereg_replace('^Comment      : ', '', $line);
                $comment = chop($comment);
                $desc[$fname] = $comment;

            }

            if ($viewCamInfo) {
            
                if (ereg('^Camera model : ', $line)) {
                    $x = ereg_replace('^Camera model : ', '', $line);
                    $x = chop($x);
                    $model[$fname] = $x;

                } elseif (ereg('^Exposure time: ', $line)) {
                    $x = ereg_replace('^Exposure time: ', '', $line);
                    if (ereg('\(', $x)) {
                        $x = ereg_replace('^.*\(', '', $x);
                        $x = ereg_replace('\).*$', '', $x);
                    }
                    $x = chop($x);
                    $shutter[$fname] = $x;

                } elseif (ereg('^Aperture     : ', $line)) {
                    $x = ereg_replace('^Aperture     : ', '', $line);
                    // make it fN.N instead of f/N.N
                    $x = ereg_replace('/', '', $x);
                    $x = chop($x);
                    $aperture[$fname] = $x;

                } elseif (ereg('^Focal length : ', $line)) {
                    $x = ereg_replace('^Focal length : ', '', $line);
                    if (ereg('35mm equiv', $x)) {
                        $x = ereg_replace('^.*alent: ', '', $x);
                        $x = chop($x);
                        $x = ereg_replace('\)$', '', $x);
                    }
                    $foclen[$fname] = $x;

                } elseif (ereg('^ISO equiv.   : ', $line)) {
                    $x = ereg_replace('ISO equiv.   : ', '', $line);
                    $x = chop($x);
                    $iso[$fname] = $x;

                } elseif (ereg('^Flash used   : Yes', $line)) {
                    $flash[$fname] = TRUE;

                } elseif (ereg('^Date/Time    : ', $line)) {
                    $x = ereg_replace('Date/Time    : ', '', $line);
                    $x = chop($x);

                    // Turn into human readable format and record
                    $timestamp[$fname] = parseExifDate($x);
                }
            }

            $line = fgets($file, 4096);
        }

        fclose($file);

        // return $desc[$image];

        $return = '';
        if ($desc[$image]) {
            $return .= $desc[$image];
        }

        if ($viewCamInfo and $model[$image]) {

            $return .= '<i>';
            if ($viewDateInfo) {
                $return .= $timestamp[$image] .' - ';
            }
            $return .= $model[$image] . '<br>';
            if ($iso[$image]) {
                $return .= 'ISO ' . $iso[$image] . ', ';
            }
            $return .= $foclen[$image] . ' ';
            $return .= $shutter[$image] . ' ';
            $return .= $aperture[$image];
            if ($flash[$image]) {
                $return .= ' ('
                        . $mig_messages[$mig_language]['flash_used']
                        . ')';
            }
        }

        return $return;

    } else {
        return '';
    }

}   // -- End of getExifDescription()



// ----------------------------------------------------------------------
//  parseExifDate() - parses an EXIF date string and returns it in a
//  more human-readable format.

function parseExifDate ($stamp)
{
    // Use global language settings
    global $mig_messages;
    global $mig_language;

    // Separate into a date and a time
    list($date,$time) = split(' ', $stamp);

    // Parse date
    list($year, $month, $day) = split(':', $date);
    // Turn numeric month into a 3-character month string
    $month = $mig_messages[$mig_language]['month'][$month];
    $date = $month .' '. $day .' '. $year;

    // Parse time
    list($hour, $minute, $second) = split(':', $time);

    // Translate into 12-hour time
    switch ($hour) {
        case '00':
            $time = '12:' .$minute. 'AM';
            break;
        case '01':
        case '02':
        case '03':
        case '04':
        case '05':
        case '06':
        case '07':
        case '08':
        case '09':
        case '10':
        case '11':
            $time = $hour .':'. $minute .'AM';
            break;
        case '12':
            $time = $hour .':'. $minute . 'PM';
            break;
        case '13':
        case '14':
        case '15':
        case '16':
        case '17':
        case '18':
        case '19':
        case '20':
        case '21':
        case '22':
        case '23':
            $time = ($hour - 12) .':'. $minute . 'PM';
            break;
    }

    // Re-join before returning so it's one string
    $stamp = $date .', '. $time;

    return ($stamp);

}   // -- End of parseExifDate()



// ----------------------------------------------------------------------
// getNewCurrDir() - replaces the silly old $newCurrDir being all
// over the place.  Especially in the URI string itself.

function getNewCurrDir( $currDir )
{

    // This just rips off the leading './' off currDir if it exists
    $newCurrDir = ereg_replace('^\.\/', '', $currDir);
    $newCurrDir = migURLencode($newCurrDir);
    return $newCurrDir;

}   // -- End of getNewCurrDir()



// ----------------------------------------------------------------------
// getNumberOfImages() - counts images in a given folder

function getNumberOfImages( $folder, $useThumbSubdir, $markerType,
                            $markerLabel )
{

    $dir = opendir($folder);    // Open directory handle

    while ($file = readdir($dir)) {
        // Skip over thumbnails
        if (!$useThumbSubdir) {  // unless $useThumbSubdir is set,
                                 // then don't waste time on this check

            if ($markerType == 'suffix'
                and eregi("_$markerLabel\.(gif|jpg|png|jpeg|jpe)$", $file)) {
                    continue;
            }
            if ($markerType == 'prefix' and ereg("^$markerLabel\_", $file)) {
                continue;
            }
        }

        // We'll look at this one only if it's a file and it matches our list
        // of approved extensions
        $ext = getFileExtension($file);
        if (is_file("$folder/$file")
            and eregi('^(jpg|gif|png|jpeg|jpe)$', $ext)) {
                $count++;
        }
    }

    return $count;

}   // -- End of getNumberOfImages()



// ----------------------------------------------------------------------
// migURLencode() - fixes a problem where "/" turns into "%2F" when
// using rawurlencode()

function migURLencode( $string )
{

    $new = $string;
    $new = rawurldecode($new);      // decode first
    $new = rawurlencode($new);      // then encode

    $new = str_replace('%2F', '/', $new);       // slash (/)

    return $new;

}   // -- End of migURLencode()



// ----------------------------------------------------------------------
// folderFrame() - frames stuff in HTML table code... avoids template
// problems in places where there are images but no folders, or vice
// versa.

function folderFrame( $input )
{

    $retval = '<table border="0" cellpadding="2" cellspacing="0">'
            . '<tr><td class="folder">' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of folderFrame()



// ----------------------------------------------------------------------
// descriptionFrame() - Same thing as folderFrame() for descriptions.

function descriptionFrame( $input )
{

    $retval = '<table border="0" cellpadding="10" width="60%">'
            . '<tr><td class="desc">' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of descriptionFrame()



// ----------------------------------------------------------------------
// imageFrame() - Same thing as folderFrame() but for image tables.

function imageFrame( $input )
{

    $retval = '<table border="0" cellpadding="5" cellspacing="0"'
            . ' class="image"><tr><td>' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of imageFrame()

?>
