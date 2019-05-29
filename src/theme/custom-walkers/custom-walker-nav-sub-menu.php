<?php
/*
 * Source : https://wordpress.stackexchange.com/questions/83388/custom-nav-walker-display-current-menu-item-children-or-siblings-on-no-children
 */

class CustomWalkerNavSubMenu extends Walker_Nav_Menu
{
    private $last_depth = 0;
    private $is_in_current_path = false;

    private function ancestorOfCurrent($item, $depth)
    {
        if ($this->last_depth >= $depth) {
            $this->is_in_current_path = false;
        }

        if (!$this->is_in_current_path) {
            $current_element_markers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' );
            $found_classes = array_intersect( $current_element_markers, $item->classes );
            $ancestor_of_current = !empty($found_classes);

            if ($ancestor_of_current) {
                $this->last_depth = $depth;
                $this->is_in_current_path = $ancestor_of_current;
            }
        }
    }

    // Don't start the top level
    function start_lvl(&$output, $depth=0, $args=array())
    {
        if( 0 == $depth || !$this->is_in_current_path )
            return;

        parent::start_lvl($output, $depth, $args);
    }

    // Don't end the top level
    function end_lvl(&$output, $depth=0, $args=array())
    {
        if( 0 == $depth || !$this->is_in_current_path )
            return;

        parent::end_lvl($output, $depth, $args);
    }

    // Don't print top-level elements
    function start_el(&$output, $item, $depth=0, $args=array(), $id = 0)
    {
        $this->ancestorOfCurrent($item, $depth);

        if (  0 == $depth || !$this->is_in_current_path )
            return;

        parent::start_el($output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth=0, $args=array())
    {
        if (  0 == $depth || !$this->is_in_current_path )
            return;

        parent::end_el($output, $item, $depth, $args);
    }

    // Only follow down one branch
    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output )
    {
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
}
