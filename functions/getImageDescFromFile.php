
// getImageDescFromFile() - Fetches an image description from a
//                          per-image comment file (used if
//                          $commentFilePerImage is TRUE)

function getImageDescFromFile ( $image, $albumDir, $currDir )
{

    $imageDesc = '';
    $fname = getFileName($image);

    if (file_exists("$albumDir/$currDir/$fname.txt")) {

        $file = fopen("$albumDir/$currDir/$fname.txt", 'r');
        $line = fgets($file, 4096);     // get first line

        // This double-check exists so that files ending without
        // a proper newline character are not truncated.
        // This says "while (not EOF) and ($line is not empty)"...
        while ( $line || ! feof($file)) {
            $line = trim($line);
            $imageDesc .= "$line ";
            $line = fgets($file, 4096); // get next line
        }

        fclose($file);

    } else {
        // File doesn't exist?  Okay, return false.
        return FALSE;
    }

    return $imageDesc;

}   // -- End of getImageDescFromFile()

