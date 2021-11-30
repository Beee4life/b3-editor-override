<?php
    /*
        Plugin Name:      B3 - Add shortcodes dropdown
        Description:      Adds a select/dropdown in the WYSIWYG editor toolbar
        Version:          1.0
        Author:           Berry Plasman
        Author URI:       https://berryplasman.com
    */

    /**
     * Add js for shortcodes
     *
     * @param $plugin_array
     * @return mixed
     */
    function b3_define_shortcodes_button_js( $plugin_array ) {
        $plugin_array[ 'add_shortcodes_menu' ] = plugin_dir_url( __FILE__ ) . 'tinymce-shortcodes.js';

        return $plugin_array;
    }

    /**
     * Register shortcodes button
     *
     * @param $buttons
     * @return array
     */
    function b3_register_shortcodes_button( $buttons ) {
        if ( is_array( $buttons ) ) {
            array_push( $buttons, 'add_shortcodes_menu' );
        }

        return $buttons;
    }

    /**
     * Add a button for custom shortcodes in WYSIWYG editor
     */
    function b3_add_shortcodes_button() {
        // check if WYSIWYG is enabled
        if ( get_user_option( 'rich_editing' ) == 'true' ) {
            add_filter( 'mce_external_plugins', 'b3_define_shortcodes_button_js' );
            add_filter( 'mce_buttons',          'b3_register_shortcodes_button' );
        }
    }
    add_action( 'admin_head', 'b3_add_shortcodes_button', 20 );
