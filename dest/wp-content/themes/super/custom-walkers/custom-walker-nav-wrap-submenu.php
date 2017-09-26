<?php 

class Wrap_Submenu extends Walker_Nav_Menu{
    function start_lvl(&$output, $depth=0, $args=array()){
        $output .= "<div class=\"sub-menu\"><ul>\n";
    }
    function end_lvl(&$output, $depth=0, $args=array()){
        $output .= "</ul></div>\n";
    }
}

?>
