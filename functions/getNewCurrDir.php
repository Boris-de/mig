<?php

// getNewCurrDir() - replaces the silly old $newCurrDir being all
//                   over the place.  Especially in the URI string itself.

function getNewCurrDir ( $currDir )
{
    // This just rips off the leading './' off currDir if it exists
    $newCurrDir = ereg_replace('^\.\/', '', $currDir);
    $newCurrDir = migURLencode($newCurrDir);

    return $newCurrDir;

}   // -- End of getNewCurrDir()

?>
