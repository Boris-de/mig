
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

        while (!feof($file)) {
            $line = trim($line);
            $imageDesc .= "$line ";
            $line = fgets($file, 4096); // get next line
        }

        fclose($file);
    }

    return $imageDesc;

}   // -- End of getImageDescFromFile()

