
// folderFrame() - frames stuff in HTML table code... avoids template
//                 problems in places where there are images but no folders,
//                 or vice versa.

function folderFrame ( $input, $randomFolderThumbs )
{
    if ($randomFolderThumbs) {
        $pad = 5;
    } else {
        $pad = 2;
    }

    $retval = '<table border="0" cellpadding="'.$pad.'" cellspacing="0">'
            . '<tr><td class="folder">' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of folderFrame()

