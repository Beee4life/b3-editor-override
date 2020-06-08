<?php
    /*
        Plugin Name:      B3 : Add shortcodes dropdown
        Description:      Adds a select/dropdown in the WYSIWYG editor toolbar
        Version:          0.1
        Author:           Berry Plasman
        Author URI:       https://www.berryplasman.com
    */

    function b3_custom_quicktags() {
        wp_enqueue_script(
            'b3_custom_quicktags',
            plugin_dir_url( __FILE__ ) . 'my-custom-quicktags.js',
            array( 'quicktags' )
        );
    }
    add_action( 'admin_print_scripts', 'b3_custom_quicktags' );
