<?php
/**
 * Plugin Name: Goliath Simple Bricks
 * Description: Append in post_content styled flexible layouts
 * Version: 1.0
 * Author: Studio Goliath
 * Author URI: http://www.studio-goliath.com/
 */

if (!class_exists('GoliathSimpleBricks')) {

    class GoliathSimpleBricks {

        function __construct() {
            $this->PLUGIN_DIR_PATH = plugin_dir_path(__FILE__);

            add_filter('the_content', array(&$this, 'display_parts'));
        }

        /**
        * Display each brick
        *
        * @param $content
        * @return string
        */
        function display_parts($content)
        {
            global $row;

            if ( function_exists('have_rows') ) {
                if ( have_rows('bricks') ) {
                    while ( have_rows('bricks') ) {
                        the_row();

                        $layout = get_row_layout();

                        $content .= $this->get_content_for_layout($layout);
                    }
                }
            }

            return $content;
        }

        function get_content_for_layout($layout)
        {
            $template = $this->get_template($layout);

            if (!empty($template)) {
                ob_start();
                require($template);
                $output = ob_get_clean();
            }

            return $output;
        }

        function get_template($layout)
        {
            $template = '';

            $template = locate_template( "/bricks/{$layout}.php", false );

            return $template;
        }
    }

    new GoliathSimpleBricks();
}
