
// folderFrame() - frames stuff in HTML table code... avoids template
//                 problems in places where there are images but no folders,
//                 or vice versa.

function folderFrame ( $input )
{

    $retval = '<table border="0" cellpadding="2" cellspacing="0">'
            . '<tr><td class="folder">' . $input . '</td></tr></table><br>';

    return $retval;

}   // -- End of folderFrame()

