
// printTemplate() - prints HTML page from a template file

function printTemplate ( $baseURL, $templateDir, $templateFile, $version,
                         $maintAddr, $folderList, $imageList, $backLink,
                         $albumURLroot, $image, $currDir, $newCurrDir,
                         $pageTitle, $prevLink, $nextLink, $currPos,
                         $description, $youAreHere, $distURL, $albumDir,
                         $pathConvertFlag, $pathConvertRegex,
                         $pathConvertTarget, $pageType, $largeSubdir,
                         $largeLink, $largeHrefStart, $largeHrefEnd,
                         $largeLinkBorder )
{
    global $REQUEST_URI;
    global $HTTP_SERVER_VARS;

    // Get URL for %%newLang%% variable
    if ($_SERVER['REQUEST_URI']) {
        $newLang = $_SERVER['REQUEST_URI'];
    } elseif ($HTTP_SERVER_VARS['REQUEST_URI']) {
        $newLang = $HTTP_SERVER_VARS['REQUEST_URI'];
    } elseif ($REQUEST_URI) {
        $newLang = $REQUEST_URI;
    }
    if (ereg('mig_dl=',$newLang)) {
        $newLang = ereg_replace('[?&]mig_dl=[^?&]*', '', $newLang);
    }
    $newLang .= '&mig_dl';

    // Only prepend a path if one isn't there.  For unix-like systems this
    // checks for a leading slash, for Windows-like system it checks for
    // a leading drive letter or an SMB share.
    if (! eregi('^(/|[a-z]:|[\\]{2})', $templateFile)) {
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

                    if (function_exists('virtual')) {
                        // virtual() doesn't like absolute paths,
                        // apparently, so just pass it a relative one.
                        $tmplDir = ereg_replace("^.*/", "", $templateDir);
                        virtual("$tmplDir/$incl_file");
                    } else {
                        include( convertIncludePath($pathConvertFlag,
                                   "$templateDir/$incl_file",
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

            // Make sure this is URL encoded
            $encodedImageURL = migURLencode($image);

            // If pagetype is large, add largeSubdir to path.
            if ($image) {
                // Get image pixel size for <IMG> element
                if ($pageType == 'image') {
                    $imageProps = GetImageSize("$albumDir/$currDir/$image");
                } elseif ($pageType == 'large') {
                    $imageProps =
                      GetImageSize("$albumDir/$currDir/$largeSubdir/$image");
                }
                $imageSize = $imageProps[3];
            }

            // List of valid tags
            $replacement_list = array (
                'baseURL', 'maintAddr', 'version', 'folderList',
                'imageList', 'backLink', 'currDir', 'newCurrDir',
                'image', 'albumURLroot', 'pageTitle', 'nextLink',
                'prevLink', 'currPos', 'description', 'youAreHere',
                'distURL', 'encodedImageURL', 'imageSize', 'newLang',
                'largeSubdir', 'largeLink', 'largeHrefStart',
                'largeHrefEnd', 'largeLinkBorder'
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

