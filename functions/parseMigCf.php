<?php

// parseMigCf() - Parse a mig.cf file.

function _readTextTillEndOfBlock($file, $needle) {
    $lines = _readLinesTillEndOfBlock($file, $needle);
    $result = '';
    foreach ($lines as $line) {
        $result .= "$line ";
    }
    return $result;
}

function _readLinesTillEndOfBlock($file, $needle, $trim = TRUE) {
    $lines = array();
    do {
        $line = fgets($file, 4096);
        if ($line === FALSE || stripos($line, $needle) !== FALSE) {
            break;
        }
        $lines[] = $trim? trim($line) : $line;
    } while (!feof($file));
    return $lines;
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

        while ($file && ! feof($file)) {
            $line = fgets($file, 4096);
            if ($line === FALSE) {
                break;
            }

            // Parse <hidden> blocks
            if (stripos($line, '<hidden>') === 0) {
                $lines = _readLinesTillEndOfBlock($file, '</hidden>');
                foreach ($lines as $line) {
                    $mig_config['hidden'][$line] = TRUE;
                }
            }

            // Parse <sort> structure
            if (stripos($line, '<sort>') === 0) {
                $lines = _readLinesTillEndOfBlock($file, '</sort>');
                foreach ($lines as $line) {
                    if (is_file("$unsafe_folder/$line")) {
                        $presort_img[$line] = TRUE;
                    } elseif (is_dir("$unsafe_folder/$line")) {
                        $presort_dir[$line] = TRUE;
                    }
                }
            }

            // Parse <bulletin> structure
            if (stripos($line, '<bulletin>') === 0) {
                $lines = _readLinesTillEndOfBlock($file, '</bulletin>', FALSE);
                $bulletin = implode('', $lines);
            }

            // Parse <comment> structure
            if (stripos($line, '<comment') === 0) {
                $commfilename = trim($line);
                $commfilename = str_replace('">', '', $commfilename);
                $commfilename = preg_replace('#^<comment "#i','',$commfilename);
                $text = _readTextTillEndOfBlock($file, '</comment>');
                if ($commfilename != NULL) {
                    $desc[$commfilename] = $text;
                }
            }

            // Parse <short> structure
            if (stripos($line, '<short') === 0) {
                $shortfilename = trim($line);
                $shortfilename = str_replace('">', '', $shortfilename);
                $shortfilename = preg_replace('#^<short "#i','',$shortfilename);
                $text = _readTextTillEndOfBlock($file, '</short>');
                if ($shortfilename != NULL) {
                    $short_desc[$shortfilename] = $text;
                }
            }

            // Parse FolderIcon lines
            if (stripos($line, 'foldericon ') === 0) {
                $x = trim($line);
                list($_, $folder, $icon) = explode(' ', $x);
                $ficons[$folder] = $icon;
            }

            // Parse UseThumb lines
            if (stripos($line, 'usethumb ') === 0) {
                $x = trim($line);
                list($_, $folder, $thumbnail) = explode(' ', $x);
                $mig_config['usethumbfile'][$folder] = $thumbnail;
            }

            // Parse FolderTemplate lines
            if (stripos($line, 'foldertemplate ') === 0) {
                $x = trim($line);
                list($_, $template) = explode(' ', $x);
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
                list($_, $fcols) = explode(' ', $x);
            }

            // Parse MaxThumbColumns lines
            if (stripos($line, 'maxthumbcolumns ') === 0) {
                $x = trim($line);
                list($_, $tcols) = explode(' ', $x);
            }

        } // end of main while() loop

        if ($file) {
            fclose($file);
        }
    }

    return array ($presort_dir, $presort_img, $desc, $short_desc,
                  $bulletin, $ficons, $template, $fcols,
                  $tcols, $maintaddr);

}   //  -- End of parseMigCf()

?>
