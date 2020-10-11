<?php

// featured.php : grab a random URL from my featured URLs file
//     written and contributed by Dan Lowe <dan@tangledhelix.com>

// file URLs are stored in - one URL per line
$URLfile = 'featured.txt';

$file = fopen($URLfile, 'r');       // open file

$line = fgets($file, 4096);
while (!feof($file)) {              // read line by line into array
    $urls[] = $line;
    $line = fgets($file, 4096);     // fetch another line
}
fclose($file);                          // close the file

srand((double)microtime()*1000000);     // choose a random URL
$randval = rand(0,(sizeof($urls)-1));

header('Location: ' . $urls[$randval]);     // print redirect header

?>
