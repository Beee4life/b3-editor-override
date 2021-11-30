<?php
    /*
        Plugin Name:      B3 : Add Quicktags
        Description:      Adds quicktags in the text toolbar
        Version:          1.0
        Author:           Berry Plasman
        Author URI:       https://berryplasman.com
    */

    function b3_custom_quicktags() {
        wp_enqueue_script(
            'b3_custom_quicktags',
            plugin_dir_url( __FILE__ ) . 'b3-custom-quicktags.js',
            array( 'quicktags' )
        );
    }
    add_action( 'admin_print_scripts', 'b3_custom_quicktags' );
