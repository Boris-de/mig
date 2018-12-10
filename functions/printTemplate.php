<?php

// printTemplate() - Prints HTML page from a template file.

function printTemplate ( $templateFile, $version, $maintAddr,
                         $folderList, $imageList, $backLink,
                         $currDir, $newCurrDir, $prevLink,
                         $nextLink, $currPos, $description, $youAreHere,
                         $distURL, $pathConvertFlag, $pathConvertRegex,
                         $pathConvertTarget, $largeLink, $largeHrefStart,
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
    if ($newLang and strpos($newLang, 'mig_dl=') !== FALSE) {
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
        print "ERROR: $templateFile does not exist!";
        exit;
    }

    $file = fopen($templateFile,'r');    // Open template file
    $line = fgets($file, 4096);                         // Get first line

    while (! feof($file)) {             // Loop until EOF

        // Look for include directives and process them
        if (strpos($line, '#include') === 0) {
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
                $templatedir = $mig_config['templatedir'];
                if (file_exists($templatedir . "/$incl_file")) {

                    if (function_exists('virtual')) {
                        // virtual() doesn't like absolute paths,
                        // apparently, so just pass it a relative one.
                        $tmplDir = preg_replace('#^.*/#', '', $templatedir);
                        virtual("$tmplDir/$incl_file");
                    } else {
                        include( convertIncludePath($pathConvertFlag,
                                            $templatedir ."/$incl_file",
                                            $pathConvertRegex, $pathConvertTarget));
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
            $image			    = isset($mig_config['image']) ? $mig_config['image'] : NULL;
            $largeSubdir		= $mig_config['largesubdir'];
            $pageTitle			= $mig_config['pagetitle'];
            $httpContentType            = $mig_config['httpContentType'];

            // Make sure this is URL encoded
            $encodedImageURL = migURLencode($image);

            $filetype=getFileType($image);
            // If pagetype is large, add largeSubdir to path.
            if ($filetype=='image' && $image) {
                // Get image pixel size for <IMG> element
				if(!is_file($mig_config['albumdir']."/$currDir/".$image)) {
					die("ERROR: Image file does not exist!");
				}
                if ($mig_config['pagetype'] == 'image') {
                    $imageProps = @GetImageSize($mig_config['albumdir']."/$currDir/"
                                               .$image);
                } elseif ($mig_config['pagetype'] == 'large') {
                    $imageProps =
                      @GetImageSize($mig_config['albumdir']."/$currDir/"
                                 . $mig_config['largesubdir']
                                 . '/'.$image);
                }
                $imageSize = $imageProps[3];
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
            $replacement_list = array (
                'baseURL', 'maintAddr', 'version', 'folderList',
                'imageList', 'backLink', 'currDir', 'newCurrDir',
                'image', 'albumURLroot', 'pageTitle', 'nextLink',
                'prevLink', 'currPos', 'description', 'youAreHere',
                'distURL', 'encodedImageURL', 'imageSize', 'newLang',
                'largeSubdir', 'largeLink', 'largeHrefStart',
                'largeHrefEnd', 'largeLinkBorder', 'httpContentType'
            );

            // Do substitution for various variables
            foreach ($replacement_list as $key => $val) {
                $value = isset($$val) ? $$val : '';
                $line = str_replace("%%$val%%", $value, $line);
            }

            print $line;                // Print resulting line
        }
        $line = fgets($file, 4096);     // Grab another line
    }

    fclose($file);
    return TRUE;

}    // -- End of printTemplate()

?>
