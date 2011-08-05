<?php
//mig.php
//just for internal use for development. not meant for public use.
//run the makefile instead
//works fine for me but not with all functions guaranteed.
//
//werner


function include_all_files($dir){
 //includes all files in a directory...
 
 $handle=opendir($dir);
while ($file = readdir ($handle)) {
    if ($file != "." && $file != ".." && $file != "CVS") {
    
    //include just .php-files, no other files...

     if (preg_match("#.php$#", $file)) {
      require($dir."/".$file); 
      }
    }
}
closedir($handle);
}

include('main/defaults.php');
include('main/pathConvert.php');

//include all functions:

$func_dir='functions';
include_all_files($func_dir);


//include all languages:

//hmm, including the language-arrays doesn't work in a function. we need the arrays global...

$lang_dir='languages';
//include_all_files($lang_dir);
$handle=opendir($lang_dir);

while ($file = readdir ($handle)) {
    if ($file != "." && $file != ".." && $file != "CVS") {
    
    //include just .php-files, no other files...

     if (preg_match("#.php$#", $file)) {
      include($lang_dir."/".$file); 
      }
    }
}
closedir($handle);

//include main-logic:



require_once('main/body.php');


?>


