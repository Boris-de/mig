<?php

// parseMigCf() - Parse a mig.cf file.

function _isEndOfBlock($line, $needle) {
    return $line === FALSE || stripos($line, $needle) === 0;
}

function parseMigCf ( $unsafe_folder )
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
    $bulletin       = '';
    $template       = $mig_config['templatedir'] . '/folder.html';
    $fcols          = $mig_config['maxFolderColumns'];
    $tcols          = $mig_config['maxThumbColumns'];
    $maintaddr      = $mig_config['maintAddr'];
    
    $mig_config['usethumbfile'] = array ();

    // Hide thumbnail subdirectory if one is in use.
    if ($mig_config['usethumbsubdir']) {
        $mig_config['hidden'][$mig_config['thumbsubdir']] = TRUE;
    }

    // Hide large subdirectory if one is in use.
    if ($mig_config['uselargeimages']) {
        $mig_config['hidden'][$mig_config['largesubdir']] = TRUE;
    }

    $unsafe_cfgfile = "$unsafe_folder/$cfgfile";
    if (file_exists($unsafe_cfgfile)) {
        $file = fopen($unsafe_cfgfile, 'r');

        while (! feof($file)) {
            $line = fgets($file, 4096);

            // Parse <hidden> blocks
            if (stripos($line, '<hidden>') === 0) {
                $line = fgets($file, 4096);
                while (!_isEndOfBlock($line, '</hidden>')) {
                    $line = trim($line);
                    $mig_config['hidden'][$line] = TRUE;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <sort> structure
            if (stripos($line, '<sort>') === 0) {
                $line = fgets($file, 4096);
                while (!_isEndOfBlock($line, '</sort>')) {
                    $line = trim($line);

                    if (is_file("$unsafe_folder/$line")) {
                        $presort_img[$line] = TRUE;
                    } elseif (is_dir("$unsafe_folder/$line")) {
                        $presort_dir[$line] = TRUE;
                    }

                    $line = fgets($file, 4096);
                }
            }

            // Parse <bulletin> structure
            if (stripos($line, '<bulletin>') === 0) {
                $line = fgets($file, 4096);
                while (!_isEndOfBlock($line, '</bulletin>')) {
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
                $mycomment = '';
                while (!_isEndOfBlock($line, '</comment')) {
                    $line = trim($line);
                    $mycomment .= "$line ";
                    $line = fgets($file, 4096);
                }
                $desc[$commfilename] = $mycomment;
            }

            // Parse <short> structure
            if (stripos($line, '<short') === 0) {
                $shortfilename = trim($line);
                $shortfilename = str_replace('">', '', $shortfilename);
                $shortfilename = preg_replace('#^<short "#i','',$shortfilename);
                $line = fgets($file, 4096);
                $myshort = '';
                while (!_isEndOfBlock($line, '</short')) {
                    $line = trim($line);
                    $myshort .= "$line ";
                    $line = fgets($file, 4096);
                }
                $short_desc[$shortfilename] = $myshort;
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

        } // end of main while() loop

        fclose($file);
    }

    return array ($presort_dir, $presort_img, $desc, $short_desc,
                  $bulletin, $ficons, $template, $fcols,
                  $tcols, $maintaddr);

}   //  -- End of parseMigCf()

?>
