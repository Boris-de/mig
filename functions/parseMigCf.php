
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

            // Parse MaintAddr lines
            if (eregi('^maintaddr ', $line)) {
                $x = trim($line);
                $maintaddr = eregi_replace('^maintaddr ', '', $x);
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
                     $fcols, $tcols, $maintaddr);
    return $retval;

}   //  -- End of parseMigCf()

