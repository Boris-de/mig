<?php

// parseMigCf() - Parse a mig.cf file.

function parseMigCf ( $directory )
{
    global $mig_config;

    // What file to parse
    $cfgfile = 'mig.cf';

    // Prototypes
    $mig_config['hidden'] = array ();
    
    $presort_dir    = array ();
    $presort_img    = array ();
    $short_desc     = array ();
    $desc           = array ();
    $ficons         = array ();
    $bulletin       = NULL;
    $template       = NULL;
    $fcols          = NULL;
    $tcols          = NULL;
    $trows          = NULL;
    $maintaddr      = NULL;
    
    $mig_config['usethumbfile'] = array ();

    // Hide thumbnail subdirectory if one is in use.
    if ($mig_config['usethumbsubdir']) {
        $mig_config['hidden'][$mig_config['thumbsubdir']] = TRUE;
    }

    // Hide large subdirectory if one is in use.
    if ($mig_config['uselargeimages']) {
        $mig_config['hidden'][$mig_config['largesubdir']] = TRUE;
    }

    if (file_exists("$directory/$cfgfile")) {
        $file = fopen("$directory/$cfgfile", 'r');
        $line = fgets($file, 4096);         // get first line

        while (! feof($file)) {

            // Parse <hidden> blocks
            if (stripos($line, '<hidden>') === 0) {
                $line = fgets($file, 4096);
                while (stripos($line, '</hidden>') !== 0) {
                    $line = trim($line);
                    $mig_config['hidden'][$line] = TRUE;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <sort> structure
            if (stripos($line, '<sort>') === 0) {
                $line = fgets($file, 4096);
                while (stripos($line, '</sort>') !== 0) {
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
            if (stripos($line, '<bulletin>') === 0) {
                $line = fgets($file, 4096);
                while (stripos($line, '</bulletin>') !== 0) {
                    $bulletin .= $line;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <comment> structure
            if (stripos($line, '<comment') === 0) {
                $commfilename = trim($line);
                $commfilename = str_replace('">', '', $commfilename);
                $commfilename = preg_replace('#^<comment "#i','',$commfilename);
                $line = fgets($file, 4096);
                while (stripos($line, '</comment') !== 0) {
                    $line = trim($line);
                    $mycomment .= "$line ";
                    $line = fgets($file, 4096);
                }
                $desc[$commfilename] = $mycomment;
                $commfilename = '';
                $mycomment = '';
            }

            // Parse <short> structure
            if (stripos($line, '<short') === 0) {
                $shortfilename = trim($line);
                $shortfilename = str_replace('">', '', $shortfilename);
                $shortfilename = preg_replace('#^<short "#i','',$shortfilename);
                $line = fgets($file, 4096);
                while (stripos($line, '</short') !== 0) {
                    $line = trim($line);
                    $myshort .= "$line ";
                    $line = fgets($file, 4096);
                }
                $short_desc[$shortfilename] = $myshort;
                $shortfilename = '';
                $myshort = '';
            }

            // Parse FolderIcon lines
            if (stripos($line, 'foldericon ') === 0) {
                $x = trim($line);
                list($y, $folder, $icon) = explode(' ', $x);
                $ficons[$folder] = $icon;
            }

            // Parse UseThumb lines
            if (stripos($line, 'usethumb ') === 0) {
                $x = trim($line);
                list($y, $folder, $thumbnail) = explode(' ', $x);
                $mig_config['usethumbfile'][$folder] = $thumbnail;
            }

            // Parse FolderTemplate lines
            if (stripos($line, 'foldertemplate ') === 0) {
                $x = trim($line);
                list($y, $template) = explode(' ', $x);
            }

            // Parse PageTitle lines
            if (stripos($line, 'pagetitle ') === 0) {
                $x = trim($line);
                $mig_config['pagetitle'] = preg_replace('#^pagetitle #i', '', $x);
            }

            // Parse MaintAddr lines
            if (stripos($line, 'maintaddr ') === 0) {
                $x = trim($line);
                $maintaddr = preg_replace('#^maintaddr #i', '', $x);
            }

            // Parse MaxFolderColumns lines
            if (stripos($line, 'maxfoldercolumns ') === 0) {
                $x = trim($line);
                list($y, $fcols) = explode(' ', $x);
            }

            // Parse MaxThumbColumns lines
            if (stripos($line, 'maxthumbcolumns ') === 0) {
                $x = trim($line);
                list($y, $tcols) = explode(' ', $x);
            }

            // Parse MaxThumbRows lines
            if (stripos($line, 'maxthumbrows ') === 0) {
                $x = trim($line);
                list($y, $trows) = explode(' ', $x);
            }

            // Get next line
            $line = fgets($file, 4096);

        } // end of main while() loop

        fclose($file);
    }

    return array ($presort_dir, $presort_img, $desc, $short_desc,
                  $bulletin, $ficons, $template, $fcols,
                  $tcols, $trows, $maintaddr);

}   //  -- End of parseMigCf()

?>
