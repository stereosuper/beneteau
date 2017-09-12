<?php
/*
 * Source : https://wordpress.stackexchange.com/questions/83388/custom-nav-walker-display-current-menu-item-children-or-siblings-on-no-children
 */

class CustomWalkerNavSubMenu extends Walker_Nav_Menu
{
    private $ID;
    private $depth;
    private $classes = array();
    private $child_count = 0;
    private $have_current = false;


    // Don't start the top level
    function start_lvl(&$output, $depth=0, $args=array()) {

        if( 0 == $depth || $this->depth != $depth )
            return;

        parent::start_lvl($output, $depth,$args);
    }

    // Don't end the top level
    function end_lvl(&$output, $depth=0, $args=array()) {
        if( 0 == $depth || $this->depth != $depth )
            return;

        parent::end_lvl($output, $depth,$args);
    }

    // Don't print top-level elements
    function start_el(&$output, $item, $depth=0, $args=array()) {

        $is_current = in_array('current-menu-item', $this->classes);

        if( 0 == $depth || ! $is_current )
            return;

        parent::start_el($output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth=0, $args=array()) {
        if( 0 == $depth )
            return;

        parent::end_el($output, $item, $depth, $args);
    }

    // Only follow down one branch
    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

        // Check if element is in the current tree to display
        $current_element_markers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' );
        $this->classes = array_intersect( $current_element_markers, $element->classes );

        // If element has a 'current' class, it is an ancestor of the current element
        $ancestor_of_current = !empty($this->classes);

        // check if the element is the actual page element we are on.
        $is_current = in_array('current-menu-item', $this->classes);

        // if it is the current element
        if($is_current) {

            // set the count / ID / and depth to use in the other functions.
            $this->child_count = ( isset($children_elements[$element->ID]) ) ? count($children_elements[$element->ID]) : 0;
            $this->ID = $element->ID;
            $this->depth = $depth;
            $this->have_current = true;

            if($this->child_count > 0) {

                // if there are children loop through them and display the kids.
                foreach( $children_elements[$element->ID] as $child ) {
                    parent::display_element( $child, $children_elements, $max_depth, $depth, $args, $output );
                }

            } else {
                // no children so loop through kids of parent item.
                foreach( $children_elements[$element->menu_item_parent] as $child ) {
                    parent::display_element( $child, $children_elements, $max_depth, $depth, $args, $output );
                }

            }
        }

        // if depth is zero and not in current tree go to the next element
        if ( 0 == $depth && !$ancestor_of_current)
            return;

        // if we aren't on the current element proceed as normal
        if(! $this->have_current )
            parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
    }
