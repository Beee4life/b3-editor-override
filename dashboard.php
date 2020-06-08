<?php
    /**
     * Content for the 'settings page'
     */
    function b3_editor_settings() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-editor' ) );
        }

        // get stored values
        $block_format_options  = get_option( 'editor_format_options', false );
        $html_options          = get_option( 'editor_html_options', false );
        $wysiwyg_row_1_options = get_option( 'editor_row1_options', false );
        $wysiwyg_row_2_options = get_option( 'editor_row2_options', false );
        ?>

        <div class="wrap editor-override-dashboard">

            <h1><?php _e( 'Editor settings', 'b3-editor' ); ?></h1>

            <p>
                <?php esc_html_e( 'Here you can select which options will be allowed in the visual/text editor.', 'b3-editor' ); ?>
                <br />
                <?php esc_html_e( 'With this plugin you\'ll also get a few (new) options which are default not available in Wordpress.', 'b3-editor' ); ?>

            </p>

            <form name="settings" action="" method="post">
                <input name="b3_settings_nonce" type="hidden" value="<?php echo wp_create_nonce( 'b3-settings-nonce' ); ?>"/>

                <table class="editor-settings">
                    <tr>
                        <td>
                            <h3>Headings</h3>
                            <?php
                                $format_options = [
                                    [
                                        'key'   => 'Paragraph',
                                        'label' => 'p',
                                    ],
                                    [
                                        'key'   => 'Heading 2',
                                        'label' => 'h2',
                                    ],
                                    [
                                        'key'   => 'Heading 3',
                                        'label' => 'h3',
                                    ],
                                    [
                                        'key'   => 'Heading 4',
                                        'label' => 'h4',
                                    ],
                                    [
                                        'key'   => 'Preformatted',
                                        'label' => 'pre',
                                    ],
                                ];
                            ?>
                            <table>
                                <?php foreach ( $format_options as $option ) { ?>
                                    <?php $selected = false; ?>
                                    <tr>
                                        <td>
                                            <label for="formats"><?php echo $option[ 'label' ]; ?></label>
                                        </td>
                                        <td>
                                            <?php if ( is_array( $block_format_options ) && in_array( $option[ 'key' ] . '=' . $option[ 'label' ], $block_format_options ) ) { ?>
                                                <?php $selected = ' checked="checked"'; ?>
                                            <?php } ?>
                                            <input name="formats[]" id="formats" type="checkbox" value="<?php echo $option[ 'key' ]; ?>=<?php echo $option[ 'label' ]; ?>" <?php echo $selected; ?>/>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                        <td>
                            <h3>WYSIWYG (top row)</h3>
                            <?php
                                $row_1_options = [
                                    'formatselect',
                                    'bold',
                                    'italic',
                                    'strikethrough',
                                    'bullist',
                                    'numlist',
                                    'blockquote',
                                    'justifyleft',
                                    'justifycenter',
                                    'justifyright',
                                    'link',
                                    'unlink',
                                    'subscript',
                                    'superscript',
                                    'wp_more',
                                    'fullscreen',
                                    'wp_adv'
                                ];
                            ?>
                            <table>
                                <?php foreach ( $row_1_options as $option ) { ?>
                                    <?php $selected = false; ?>
                                    <tr>
                                        <td>
                                            <label for="wysiwyg_1"><?php echo $option; ?></label>
                                        </td>
                                        <td>
                                            <?php if ( is_array( $wysiwyg_row_1_options ) && in_array( $option, $wysiwyg_row_1_options ) ) { ?>
                                                <?php $selected = ' checked="checked"'; ?>
                                            <?php } ?>
                                            <input name="wysiwyg_1[]" id="wysiwyg_1" type="checkbox" value="<?php echo $option; ?>" <?php echo $selected; ?>/>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                        <td>
                            <h3>WYSIWYG (bottom row)</h3>
                            <?php
                                $row_2_options = [
                                    'nosecondrow',
                                    'strikethrough',
                                    'hr',
                                    'underline',
                                    'justifyfull',
                                    'forecolor',
                                    'pastetext',
                                    'removeformat',
                                    'media',
                                    'charmap',
                                    'outdent',
                                    'indent',
                                    'undo',
                                    'redo',
                                    'wp_help'
                                ];
                            ?>
                            <table>
                                <?php foreach ( $row_2_options as $option ) { ?>
                                    <?php $selected = false; ?>
                                    <tr>
                                        <td>
                                            <label for="wysiwyg_2"><?php echo $option; ?></label>
                                        </td>
                                        <td>
                                            <?php if ( is_array( $wysiwyg_row_2_options ) && in_array( $option, $wysiwyg_row_2_options ) ) { ?>
                                                <?php $selected = ' checked="checked"'; ?>
                                            <?php } ?>
                                            <input name="wysiwyg_2[]" id="wysiwyg_2" type="checkbox" value="<?php echo $option; ?>" <?php echo $selected; ?>/>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                        <td>
                            <h3>HTML</h3>
                            <?php
                                $text_editor_options = [
                                    'strong',
                                    'em',
                                    'link',
                                    'block',
                                    'del',
                                    'ins',
                                    'img',
                                    'ul',
                                    'ol',
                                    'li',
                                    'code',
                                    'more',
                                    'close',
                                    'dfw'
                                ];
                            ?>
                            <table>
                                <?php foreach ( $text_editor_options as $option ) { ?>
                                    <?php $selected = false; ?>
                                    <tr>
                                        <td>
                                            <label for="html"><?php echo $option; ?></label>
                                        </td>
                                        <td>
                                            <?php if ( is_array( $html_options ) && in_array( $option, $html_options ) ) { ?>
                                                <?php $selected = ' checked="checked"'; ?>
                                            <?php } ?>
                                            <input name="html[]" id="html" type="checkbox" value="<?php echo $option; ?>" <?php echo $selected; ?>/>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                </table>

                <br/>
                <input type="submit" class="button button-primary" value="<?php _e( 'Save options', 'b3-editor' ); ?>"/>

                <h3>Notes</h3>
                <ul>
                    <li>
                        <?php esc_html_e( 'media (new) = allows embedding of a video or an image', 'b3-editor' ); ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'underline (new) = use with care since it can be mistake for a link. This option is default disabled in Wordpress, because of this reason.', 'b3-editor' ); ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'wp_adv = toggle second row', 'b3-editor' ); ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'dfw = distract free writing mode (fullscreen)', 'b3-editor' ); ?>
                    </li>
                </ul>

                <h3>IFS</h3>
                <ul>
                    <li>
                        <?php esc_html_e( 'If "formatselect" is not checked, headings option won\'t be stored.', 'b3-editor' ); ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'If "formatselect" is checked, and nothing is selected for headings option, p,h3,h4 will be set.', 'b3-editor' ); ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'If "nosecondrow" is checked, "wp_adv" is removed (if set) and all other options for row 2.', 'b3-editor' ); ?>
                    </li>
                    <li>
                        <?php esc_html_e( 'If no options are selected at all, all buttons are disabled.', 'b3-editor' ); ?>
                    </li>
                </ul>
            </form>

        </div>
    <?php }
