

// parseMigCf() - Parse a mig.cf file.

function parseMigCf ( $directory )
{
    global $mig_config;

    // What file to parse
    $cfgfile = "mig.cf";

    // Prototypes
    $hidden         = array ();
    $presort_dir    = array ();
    $presort_img    = array ();
    $short_desc     = array ();
    $desc           = array ();
    $ficons         = array ();
    $usethumbfile   = array ();

    // Hide thumbnail subdirectory if one is in use.
    if ($mig_config["usethumbsubdir"]) {
        $hidden[$mig_config["thumbsubdir"]] = TRUE;
    }

    // Hide large subdirectory if one is in use.
    if ($mig_config["uselargeimages"]) {
        $hidden[$mig_config["largesubdir"]] = TRUE;
    }

    if (file_exists("$directory/$cfgfile")) {
        $file = fopen("$directory/$cfgfile", "r");
        $line = fgets($file, 4096);     		// get first line

        while (! feof($file)) {

            // Parse <hidden> blocks
            if (eregi("^<hidden>", $line)) {
                $line = fgets($file, 4096);
                while (! eregi("^</hidden>", $line)) {
                    $line = trim($line);
                    $hidden[$line] = TRUE;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <sort> structure
            if (eregi("^<sort>", $line)) {
                $line = fgets($file, 4096);
                while (! eregi("^</sort>", $line)) {
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
            if (eregi("^<bulletin>", $line)) {
                $line = fgets($file, 4096);
                while (! eregi("^</bulletin>", $line)) {
                    $bulletin .= $line;
                    $line = fgets($file, 4096);
                }
            }

            // Parse <comment> structure
            if (eregi("^<comment", $line)) {
                $commfilename = trim($line);
                $commfilename = str_replace("\">", "", $commfilename);
                $commfilename = eregi_replace("^<comment \"","",$commfilename);
                $line = fgets($file, 4096);
                while (! eregi("^</comment", $line)) {
                    $line = trim($line);
                    $mycomment .= "$line ";
                    $line = fgets($file, 4096);
                }
                $desc[$commfilename] = $mycomment;
                $commfilename = "";
                $mycomment = "";
            }

            // Parse <short> structure
            if (eregi("^<short", $line)) {
                $shortfilename = trim($line);
                $shortfilename = str_replace("\">", "", $shortfilename);
                $shortfilename = eregi_replace("^<short \"","",$shortfilename);
                $line = fgets($file, 4096);
                while (! eregi("^</short", $line)) {
                    $line = trim($line);
                    $myshort .= "$line ";
                    $line = fgets($file, 4096);
                }
                $short_desc[$shortfilename] = $myshort;
                $shortfilename = "";
                $myshort = "";
            }

            // Parse FolderIcon lines
            if (eregi("^foldericon ", $line)) {
                $x = trim($line);
                list($y, $folder, $icon) = explode(" ", $x);
                $ficons[$folder] = $icon;
            }

            // Parse UseThumb lines
            if (eregi("^usethumb ", $line)) {
                $x = trim($line);
                list($y, $folder, $thumbnail) = explode(" ", $x);
                $usethumbfile[$folder] = $thumbnail;
            }

            // Parse FolderTemplate lines
            if (eregi("^foldertemplate ", $line)) {
                $x = trim($line);
                list($y, $template) = explode(" ", $x);
            }

            // Parse PageTitle lines
            if (eregi("^pagetitle ", $line)) {
                $x = trim($line);
                $pagetitle = eregi_replace("^pagetitle ", "", $x);
            }

            // Parse MaintAddr lines
            if (eregi("^maintaddr ", $line)) {
                $x = trim($line);
                $maintaddr = eregi_replace("^maintaddr ", "", $x);
            }

            // Parse MaxFolderColumns lines
            if (eregi("^maxfoldercolumns ", $line)) {
                $x = trim($line);
                list($y, $fcols) = explode(" ", $x);
            }

            // Parse MaxThumbColumns lines
            if (eregi("^maxthumbcolumns ", $line)) {
                $x = trim($line);
                list($y, $tcols) = explode(" ", $x);
            }

            // Parse MaxThumbRows lines
            if (eregi("^maxthumbrows ", $line)) {
                $x = trim($line);
                list($y, $trows) = explode(" ", $x);
            }

            // Get next line
            $line = fgets($file, 4096);

        } // end of main while() loop

        fclose($file);
    }

    return array ($hidden, $presort_dir, $presort_img, $desc, $short_desc,
                  $bulletin, $ficons, $template, $pagetitle, $fcols,
                  $tcols, $trows, $maintaddr, $usethumbfile);

}   //  -- End of parseMigCf()

