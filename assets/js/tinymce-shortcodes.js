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
