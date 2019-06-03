<?php 

class Wrap_Submenu extends Walker_Nav_Menu{
    function start_lvl(&$output, $depth=0, $args=array()){
        if ($depth === 1) {
            $output .= "<div class='sub-menu-wrapper'><ul class='sub-menu'>";
        } else {
            $output .= "<ul class='sub-menu'>";
        }
    }
    function end_lvl(&$output, $depth=0, $args=array()){
        if ($depth === 1) {
            $output .= "</ul></div>";
        } else {
            $output .= "</ul>";
        }
    }
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0){
        $is_button = get_field('is_button', $item->ID);

        switch ($depth) {
            case 0:
                $lvl = 'first-level';
                break;
            case 1:
                $lvl = 'second-level';
                break;
            case 2:
                $lvl = 'third-level';
                break;
            default:
                $lvl = '';
                break;
        }

        $output .= "<li class='$lvl'>";

        
        if ($is_button) {
            if ($depth === 0) {
                $output .= "<button type='button' aria-expanded='false'>". $item->title .'</button>';
            } else {
                $output .= "<span role='button' aria-expanded='false' tab-index='0'>". $item->title .'</span>';
            }
        } else {
            $url = $item->url;

            $attributes = '';
            $attributes .= $item->current ? 'aria-current="true"' : '';

            $output .= "<a href='$url' $attributes>". $item->title .'</a>';
        }
    }
    function end_el(&$output, $item, $depth=0, $args=array()){
        $is_button = get_field('is_button', $item->ID);
        $output .= '</li>';
    }
}

?>
