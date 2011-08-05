<?php
function replaceString($val, $formattable){

        // $changeflag is used to tell us if we should bother
        // printing this block at all.  If none of the format
        // characters in this block can be expanded, we never set
        // $changeflag to TRUE.  If it's not TRUE at the end of this
        // while(), the block is just dumped.
        $changeflag = FALSE;

     
        // Keep on going until every %X atom has been examined and
        // expanded.
     
        while (preg_match('#%([a-zA-Z])#', $val , $lettermatch)) {
  
            // which letter matched?
            $letter = $lettermatch[1];

            // If this can be expanded, do so.  If it can be,
            // set $changeflag to TRUE so we know to include this
            // block instead of dumping it.
            if ($formattable[$letter]) {
                $newtext = $formattable[$letter];
                $changeflag = TRUE;
            }

            // Do interpolation
            $val = str_replace("%$letter", $newtext, $val);
            $newtext = "";
         }
         
        // Only if $changeflag is TRUE do we bother tacking this
        // onto the final product.
        if ($changeflag) $newstr = $val;
        
        return $newstr;
 }           
?>