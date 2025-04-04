<?php

// printTemplate() - Prints HTML page from a template file.

function printTemplate ( $templateFile, $maintAddr,
                         $folderList, $imageList, $backLink,
                         $unsafe_currDir, $newCurrDir, $prevLink,
                         $nextLink, $currPos, $description, $youAreHere,
                         $pathConvert, $largeLink, $largeHrefStart,
                         $largeHrefEnd, $largeLinkBorder )
{
    global $mig_config;

    global $REQUEST_URI;
    global $HTTP_SERVER_VARS;

    $newLang = '';
    // Get URL for %%newLang%% variable
    if (isset($_SERVER['REQUEST_URI'])) {
        $newLang = $_SERVER['REQUEST_URI'];
    } elseif (isset($HTTP_SERVER_VARS['REQUEST_URI'])) {
        $newLang = $HTTP_SERVER_VARS['REQUEST_URI'];
    } elseif (isset($REQUEST_URI)) {
        $newLang = $REQUEST_URI;
    }
    if ($newLang !== '' and strpos($newLang, 'mig_dl=') !== FALSE) {
        $newLang = preg_replace('#[?&]mig_dl=[^?&]*#', '', $newLang);
    }
    $newLang .= '&mig_dl';

    // Only prepend a path if one isn't there.  For unix-like systems this
    // checks for a leading slash, for Windows-like system it checks for
    // a leading drive letter or an SMB share.
    if (! preg_match('#^(/|[a-z]:|[\\\\]{2})#i', $templateFile)) {
        $templateFile = $mig_config['albumdir'] . '/' . $newCurrDir . '/' . $templateFile;
    }

    // Panic if the template file doesn't exist.
    if (! file_exists($templateFile)) {
        exit("ERROR: $templateFile does not exist!");
    }

    $file = fopen($templateFile,'r');    // Open template file
    if ($file === FALSE) {
        die("ERROR: template cannot be opened!");
    }
    $line = fgets($file, 4096);                         // Get first line

    while ($line !== FALSE && !feof($file)) {             // Loop until EOF

        // Look for include directives and process them
        if (strpos($line, '#include') === 0) {
            $orig_line = $line;
            $line = trim($line);
            $line = str_replace('#include "', '', $line);
            $line = str_replace('";', '', $line);
            if (strstr($line, '/') !== FALSE) {
                $line = '<!-- ERROR: #include directive failed.'
                      . ' Path included a "/" character, indicating'
                      . ' an absolute or relative path.  All included'
                      . ' files must be located in the templates/'
                      . ' subdirectory. Directive was:'
                      . "\n     $orig_line\n-->\n";
                print $line;
            } else {
                $incl_file = $line;
                $templatedir = $mig_config['templatedir'];
                if (file_exists($templatedir . "/$incl_file")) {

                    if (function_exists('virtual')) {
                        // virtual() doesn't like absolute paths,
                        // apparently, so just pass it a relative one.
                        $tmplDir = preg_replace('#^.*/#', '', $templatedir);
                        /** @psalm-suppress PossiblyInvalidCast */
                        assert(virtual("$tmplDir/$incl_file") == TRUE);
                    } else {
                        include($pathConvert->convertIncludePath($templatedir ."/$incl_file"));
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

            $albumURLroot		= $mig_config['albumurlroot'];
            $baseURL			= $mig_config['baseurl'];
            $enc_image			= isset($mig_config['enc_image']) ? $mig_config['enc_image'] : '';
            $unsafe_image		= isset($mig_config['unsafe_image']) ? $mig_config['unsafe_image'] : '';
            $imageSize          = '';
            $largeSubdir		= $mig_config['largesubdir'];
            $pageTitle			= $mig_config['pagetitle'];
            $charset            = $mig_config['charset'];
            $httpContentType    = $mig_config['httpContentType'];

            // Make sure this is URL encoded
            $encodedImageURL = migURLencode($unsafe_image);

            $filetype=getFileType($unsafe_image);
            // If pagetype is large, add largeSubdir to path.
            if ($filetype=='image' && $unsafe_image !== '') {
                // Get image pixel size for <IMG> element
                $unsafe_abs_image = $mig_config['albumdir'] . "/$unsafe_currDir/" . $unsafe_image;
                if(!is_file($unsafe_abs_image)) {
                    exit("ERROR: Image file does not exist!");
				}
                if ($mig_config['pagetype'] == 'image') {
                    $imageProps = @GetImageSize($unsafe_abs_image);
                } elseif ($mig_config['pagetype'] == 'large') {
                    $unsafe_abs_large_image = $mig_config['albumdir'] . "/$unsafe_currDir/" . $mig_config['largesubdir'] . '/' . $unsafe_image;
                    $imageProps = @GetImageSize($unsafe_abs_large_image);
                } else {
                    $imageProps = FALSE;
                }
                if ($imageProps) {
                    $imageSize = $imageProps[3];
                }
            } elseif ($filetype) { // known and !image -> display icon with link
                $largeHrefStart =
                       "<a href=\"$albumURLroot/$newCurrDir/$encodedImageURL\">";
                $largeHrefEnd = "</a><br />$encodedImageURL";
                $albumURLroot='.';
                $newCurrDir='images';
                switch ($filetype) {
                    case 'video': $encodedImageURL=$mig_config['movie_icon']; break;
                    case 'audio': $encodedImageURL=$mig_config['music_icon']; break;
                    default: $encodedImageURL=$mig_config['nothumb_icon']; break;
                }
            }

            // List of valid tags
            $replacement_map = [
                'baseURL' => $baseURL,
                'maintAddr' => $maintAddr,
                'version' => $mig_config['version'],
                'folderList' => $folderList,
                'imageList' => $imageList,
                'backLink' => $backLink,
                'currDir' => migHtmlSpecialChars($unsafe_currDir),
                'newCurrDir' => $newCurrDir,
                'image' => $enc_image,
                'albumURLroot' => $albumURLroot,
                'pageTitle' => $pageTitle,
                'nextLink' => $nextLink,
                'prevLink' => $prevLink,
                'currPos' => $currPos,
                'description' => $description,
                'youAreHere' => $youAreHere,
                'distURL' => $mig_config['distURL'],
                'encodedImageURL' => $encodedImageURL,
                'imageSize' => $imageSize,
                'newLang' => $newLang,
                'largeSubdir' => $largeSubdir,
                'largeLink' => $largeLink,
                'largeHrefStart' => $largeHrefStart,
                'largeHrefEnd' => $largeHrefEnd,
                'largeLinkBorder' => $largeLinkBorder,
                'charset' => $charset,
                'httpContentType' => $httpContentType,
            ];

            // Do substitution for various variables
            foreach ($replacement_map as $key => $value) {
                $value = $value !== NULL ? $value : '';
                $line = str_replace("%%$key%%", $value, $line);
            }

            print $line;                // Print resulting line
        }
        $line = fgets($file, 4096);     // Grab another line
    }

    fclose($file);
    return TRUE;

}    // -- End of printTemplate()

?>
