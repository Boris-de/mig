<?

// buildTable() - Frames stuff in HTML table code so it's ready to use as a stand-alone
//                element in a template.

function buildTable ( $input, $tableclass, $tablesummary )
{
    global $mig_config;
    
    $retval = '<table summary="' . $tablesummary
            . '" border="0" cellpadding="0" cellspacing="0"><tbody>'
            . "\n" . ' <tr><td class="' . $tableclass . '">'
            . $input . "\n" . ' </td></tr>' . "\n" . '</tbody></table>';

    return $retval;

}   // -- End of buildTable()

?>