<html><body>

<?

    // Contributed by Todd Eddy <vrillusions@neo.rr.com>

    function randsalt ( $length ) {

        // Will take a random character out of the following list up to length specified
        $list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        mt_srand((double)microtime()*1000000);

        $newstring = "";

        if ($length > 0) {
            while(strlen($newstring) < $length) {
                $newstring .= $list[mt_rand(0, strlen($list)-1)];
            }
        }
        return $newstring;
    }


    if ($action) {
        if ($salt) {
             $crypttext = crypt($text, $salt);
        } else {
             // They erased the salt, generate another one
             $crypttext = crypt($text, RandSalt(2));
        }
        echo "The encrypted form of the text is: <br> $crypttext";

    } else {

?>

    <form method="post">
    <input type="hidden" name="action" value="1">
    Enter text to be encrypted: <br>
    <input type="text" name="text"><br>
    <br>
    Enter the salt value (what it's encrypted against).  A random salt value has been generated for you: <br>
    <input type="text" name="salt" size="2" value="<? echo RandSalt(2); ?>"><br>
    <input type="submit" value="submit">
    </form>

<?
    }  // End of else block
?>

</body></html>
