<?php
    /*
        Plugin Name:      B3 - Editor Override
        Description:      Override editor toolbar buttons for the WYSIWYG/HTML editor.
        Version:          1.0
        Author:           Beee
        Author URI:       https://berryplasman.com
        Text Domain:      b3-editor
    */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    if ( ! class_exists( 'B3_EditorOverride' ) ):

        class B3_EditorOverride {
            var $settings;

            function __construct() {
            }

            function initialize() {
                // vars
                $this->settings = array(
                    'path'    => trailingslashit( dirname( __FILE__ ) ),
                    'version' => '1.0',
                );

                register_activation_hook(__FILE__,      array( $this, 'b3_plugin_activation' ) );
                register_deactivation_hook( __FILE__,   array( $this, 'b3_plugin_deactivation' ) );

                // actions
                add_action( 'admin_menu',               array( $this, 'b3_add_admin_pages' ) );
                add_action( 'admin_enqueue_scripts',    array( $this, 'b3_enqueue_scripts_backend' ) );
                add_action( 'admin_print_scripts',      array( $this, 'b3_custom_quicktags' ) );

                // filters
                add_filter( 'mce_buttons',              array( $this, 'b3_override_editor_mce_buttons_row1' ) );
                add_filter( 'mce_buttons_2',            array( $this, 'b3_override_editor_mce_buttons_row2' ) );
                add_filter( 'tiny_mce_before_init',     array( $this, 'b3_tinymce_remove_unused_formats' ) );
                add_filter( 'quicktags_settings',       array( $this, 'b3_override_text_editor_button' ) );
                add_filter( 'admin_init',               array( $this, 'b3_store_form_settings' ) );

                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( $this, 'b3_override_settings_link' ) );
            }


            function b3_custom_quicktags() {
                wp_enqueue_script(
                    'b3_custom_quicktags',
                    plugin_dir_url( __FILE__ ) . '/assets/js/quicktags.js',
                    array( 'quicktags' )
                );
            }

            /**
             * Preset settings when plugin is activated
             */
            function b3_plugin_activation() {

                $html_options = [
                    'strong',
                    'em',
                    'link',
                    'ul',
                    'ol',
                    'li',
                ];
                $row1_options = [
                    'bold',
                    'italic',
                    'bullist',
                    'numlist',
                ];

                update_option( 'editor_html_options', $html_options, true );
                update_option( 'editor_row1_options', $row1_options, true );
            }

            /**
             * Stuff to do when plugin is deactivated.
             * Since this is installed through composer, the delete function is not 'hit', so to keep the datase clean,
             * we delete the options on plugin deactivation.
             */
            function b3_plugin_deactivation() {
                delete_option( 'editor_format_options' );
                delete_option( 'editor_row1_options' );
                delete_option( 'editor_row2_options' );
                delete_option( 'editor_html_options' );
            }

            /**
             * Adds a page to admin sidebar menu for settings
             */
            public function b3_add_admin_pages() {
                include( 'dashboard.php' );
                add_submenu_page(
                    'options-general.php',
                    'Editor settings',
                    'Editor settings',
                    'manage_options',
                    'b3-editor-settings',
                    'b3_editor_settings'
                );
            }


            /**
             *  Enqueue CSS on back-end
             */
            public function b3_enqueue_scripts_backend() {
                wp_enqueue_style( 'b3-editor', plugins_url( 'assets/css/b3-editor.css', __FILE__ ), '', $this->settings[ 'version' ] );
            }


            /**
             * TinyMCE: First line toolbar customizations
             *
             * @src: https://www.kevinleary.net/customizing-tinymce-wysiwyg-editor-wordpress/
             *
             * @param $buttons
             *
             * @return array
             */
            public function b3_override_editor_mce_buttons_row1( $buttons ) {

                $selected = get_option( 'editor_row1_options' );

                if ( is_array( $selected ) && ! empty( $selected ) ) {
                    $buttons = $selected;
                } else {
                    $buttons = [ 'dummy' ];
                }

                return $buttons;

            }

            /**
             * TinyMCE: Remove second line toolbar customizations
             *
             * @param $buttons
             *
             * @return array
             */
            public function b3_override_editor_mce_buttons_row2( $buttons ) {

                $selected = get_option( 'editor_row2_options' );

                if ( is_array( $selected ) && ! empty( $selected ) ) {
                    $buttons = $selected;
                } else {
                    $buttons = [];
                }

                return $buttons;

            }

            /**
             * Modify TinyMCE editor to override formats.
             *
             * @src: https://www.jowaltham.com/modify-tinymce-editor/
             */
            public function b3_tinymce_remove_unused_formats( $options ) {
                $block_format_options = get_option( 'editor_format_options' );
                if ( is_array( $block_format_options ) && ! empty( $block_format_options ) ) {
                    $options[ 'block_formats' ] = implode( ';', $block_format_options );
                }

                return $options;
            }

            /**
             * Rebuild text editor toolbar
             *
             * @https://github.com/bueltge/AddQuicktag/blob/master/inc/class-remove-quicktags.php
             *
             * @param $options
             *
             * @return mixed
             */
            public function b3_override_text_editor_button( $options ) {

                $selected = get_option( 'editor_html_options' );

                if ( false != $selected ) {
                    $option_string        = implode( ',', $selected );
                    $options[ 'buttons' ] = $option_string;
                } else {
                    $options[ 'buttons' ] = 'dummy';
                }

                return $options;

            }

            /*
             * Add settings link on plugin page
             */
            public function b3_override_settings_link( $links ) {
                $settings_link = '<a href="options-general.php?page=b3-editor-settings">' . esc_html__( 'Settings', 'b3-editor' ) . '</a>';
                array_unshift( $links, $settings_link );

                return $links;
            }

            /**
             * Store settings
             */
            function b3_store_form_settings() {
                if ( isset( $_POST[ 'b3eo_settings_nonce' ] ) ) {
                    if ( ! wp_verify_nonce( $_POST[ 'b3eo_settings_nonce' ], 'b3eo-settings-nonce' ) ) {
                        // @TODO: throw error
                        return;
                    } else {
                        delete_option( 'editor_format_options' );
                        delete_option( 'editor_row1_options' );
                        delete_option( 'editor_row2_options' );
                        delete_option( 'editor_html_options' );
                        if ( isset( $_POST[ 'formats' ] ) && ! empty( $_POST[ 'formats' ] ) ) {
                            update_option( 'editor_format_options', $_POST[ 'formats' ], true );
                        }
                        if ( isset( $_POST[ 'wysiwyg_1' ] ) && ! empty( $_POST[ 'wysiwyg_1' ] ) ) {
                            update_option( 'editor_row1_options', $_POST[ 'wysiwyg_1' ], true );
                            if ( ! in_array( 'formatselect', $_POST[ 'wysiwyg_1' ] ) ) {
                                delete_option( 'editor_format_options' );
                            } else {
                                $stored_headings = get_option( 'editor_format_options' );
                                if ( false ==  $stored_headings ) {
                                    // preset some values if nothing is selected
                                    $headings = [
                                        'Paragraph=p',
                                        'Heading 3=h3',
                                        'Heading 4=h4',
                                    ];
                                    update_option( 'editor_format_options', $headings, true );
                                }
                            }
                        }
                        if ( isset( $_POST[ 'wysiwyg_2' ] ) && ! empty( $_POST[ 'wysiwyg_2' ] ) ) {

                            if ( in_array( 'nosecondrow', $_POST[ 'wysiwyg_2' ] ) ) {
                                $options       = [ 'nosecondrow' ];
                                $row_1_options = get_option( 'editor_row1_options' );
                                if ( ( $key = array_search( 'wp_adv', $row_1_options ) ) !== false ) {
                                    unset( $row_1_options[ $key ] );
                                    update_option( 'editor_row1_options', $row_1_options, true );
                                }
                            } else {
                                $options = $_POST[ 'wysiwyg_2' ];
                            }
                            update_option( 'editor_row2_options', $options, true );
                        }
                        if ( isset( $_POST[ 'html' ] ) && ! empty( $_POST[ 'html' ] ) ) {
                            update_option( 'editor_html_options', $_POST[ 'html' ], true );
                        }
                    }
                }
            }

        }

        /**
         * Initialize the editor
         *
         * @return B3_EditorOverride
         */
        function init_editor_overrides() {
            global $editor_overrides_plugin;

            if ( ! isset( $editor_overrides_plugin ) ) {
                $editor_overrides_plugin = new B3_EditorOverride();
                $editor_overrides_plugin->initialize();
            }

            return $editor_overrides_plugin;
        }

        // initialize
        init_editor_overrides();

    endif; // class_exists check
