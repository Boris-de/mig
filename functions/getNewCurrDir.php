<?php

// getNewCurrDir() - Replaces the silly old $newCurrDir being all over the place.  Especially
//                   in the URI string.

function getNewCurrDir ( $unsafe_currDir )
{
    // This just rips off the leading "./" off currDir if it exists
    $unsafe_newCurrDir = preg_replace('#^\./#', '', $unsafe_currDir);
    return migURLencode($unsafe_newCurrDir);

}   // -- End of getNewCurrDir()

?>