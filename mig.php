<?

function include_all_files($dir){
 //includes all files in a directory...
 
 $handle=opendir($dir);
while ($file = readdir ($handle)) {
    if ($file != "." && $file != ".." && $file != "CVS") {
    
    //include just .php-files, no other files...

     if (ereg(".php$", $file)){ 
      include($dir."/".$file); 
     }
    }
}
closedir($handle);
}



//include all functions:

$func_dir='functions';
include_all_files($func_dir);


//include all languages:

$lang_dir='languages';
include_all_files($lang_dir);

//include main-logic:

require_once('main/body.php');


?>


