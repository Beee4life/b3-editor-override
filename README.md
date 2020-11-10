# B3 Editor override plugin

This is a Wordpress plugin, with which you can 'override' Wordpress' TinyMCE's WYSIWYG and text editor.

The settings for this plugin can be found under Settings > Editor settings.

**Contents**

- [Activation](#activate)
- [Deactivation](#deactivate)
- [Filters](#filters)
- [Quicktags](#quicktags)
- [Shortcodes](#shortcodes)
- [Notes](#notes)
- [Source(s)](#sources)

<a name="activate"></a>
### Activation 

If you activate this plugin only a few most used toolbar settings are preset, because if you use this plugin, you most likely want to limit something anyway ;)

<a name="deactivate"></a>
### Deactivation 

All stored options are removed.

<a name="filters"></a>
### Filters

If you want to combine buttons from row 1 and 2 in the TinyMCE editor, you can create your own array and hook into `mce_buttons` with a priority of 11 or higher/later. You then need to hook into `mce_buttons_2` and set the second row to an empty array.

#### Example 

```
function b3_custom_toolbar_row1( $buttons ) {

    $buttons = [
        'bold',
        'italic',
        'bullist',
        'numlist',
        'pastetext',
        'removeformat',
    ];
    
    return $buttons; 
}
add_filter( 'mce_buttons', 'b3_custom_toolbar_row1', 11 );

function b3_custom_toolbar_row2( $buttons ) {
    return []; 
}
add_filter( 'mce_buttons_2', 'b3_custom_toolbar_row2', 11 );
```

<a name="quicktags"></a>
### Quicktags

Ever since WordPress introduced [Quicktags](https://codex.wordpress.org/Quicktags_API), people have been wanting to customize them. Naturally, a lot of tutorials popped up, demonstrating how to do this. The problem is that a lot of them require that you edit quicktags.js, a file in WordPress.

You can easily do so by creating a simple custom plugin with 1 function.

You can find the example below as plugin in the `examples` folder.

#### Example 

```
/*
Plugin Name: Custom Quicktags
Version: 1.0
*/

function b3_quicktags() {
    wp_enqueue_script(
        'b3_quicktags',
        plugin_dir_url( __FILE__ ) . 'b3-quicktags.js',
        array( 'quicktags' )
    );
}
add_action( 'admin_print_scripts', 'my_custom_quicktags' );
```

b3-quicktags.js could contain something like the following for an html tag:

```
edButtons[edButtons.length] = new edButton( 'h3', 'h3', '<h3>', '</h3>' );
``` 

b3-quicktags.js could contain something like the following for the `[embed]` shortcode:

```
edButtons[edButtons.length] = new edButton( 'embed', 'embed', '[embed]', '[/embed]' );
``` 

<a name="shortcodes"></a>
### Shortcodes 

If you have some shortcodes which you frequently use, you can add them with a simple custom plugin.

You can find the example below as plugin in the `examples` folder.

#### Example 

```
/*
Plugin Name: Add shortcodes dropdown
Version: 1.0
*/

/**
 * Add js for shortcodes
 *
 * @param $plugin_array
 * @return mixed
 */
function eo_define_shortcodes_button_js( $plugin_array ) {
    $plugin_array[ 'add_shortcodes_menu' ] = plugins_url( '/tinymce-shortcodes.js', __FILE__ );

    return $plugin_array;
}

/**
 * Register shortcodes button
 *
 * @param $buttons
 * @return array
 */
function eo_register_shortcodes_button( $buttons ) {
    if ( is_array( $buttons ) ) {
        array_push( $buttons, 'add_shortcodes_menu' );
    }

    return $buttons;
}

/**
 * Add a button for custom shortcodes in WYSIWYG editor
 */
function eo_add_shortcodes_button() {
    // check if WYSIWYG is enabled
    if ( get_user_option( 'rich_editing' ) == 'true' ) {
        add_filter( 'mce_external_plugins', 'eo_define_shortcodes_button_js' );
        add_filter( 'mce_buttons',          'eo_register_shortcodes_button' );
    }
}
add_action( 'admin_head', 'eo_add_shortcodes_button', 20 );
```

tinymce-shortcodes.js should contain the following:

```
(function() {
    tinymce.PluginManager.add('add_shortcodes_menu', function( editor, url ) {
        editor.addButton( 'add_shortcodes_menu', {
            type: 'menubutton',
            text: 'Button label',
            icon: false,
            menu: [
                {
                    text: 'Popup title',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Title in dropdown',
                            body: [{
                                type: 'textbox',
                                name: 'title',
                                label: 'Label before input'
                            }],
                            onsubmit: function( e ) {
                                editor.insertContent( '[shortcode]' + e.data.title + '[/shortcode]');
                            }
                        });
                    }
                }
            ]
        });
    });
})();
```

<a name="notes"></a>
### Notes 

When this plugin is active, H1, H5 and H6 are removed from the available options because of SEO reasons.

This plugin is NOT tested yet with Wordpress' new Gutenberg editor.

<a name="sources"></a>
### Source(s) 

WYSIWYG: https://www.gavick.com/blog/wordpress-tinymce-custom-buttons

HTML: http://scribu.net/wordpress/right-way-to-add-custom-quicktags.html
