

// getNewCurrDir() - Replaces the silly old $newCurrDir being all over the place.  Especially
//                   in the URI string.

function getNewCurrDir ( $currDir )
{
    global $mig_config;
    
    // This just rips off the leading "./" off currDir if it exists
    $newCurrDir = ereg_replace("^\.\/", "", $currDir);
    $newCurrDir = migURLencode($newCurrDir);

    return $newCurrDir;

}   // -- End of getNewCurrDir()

