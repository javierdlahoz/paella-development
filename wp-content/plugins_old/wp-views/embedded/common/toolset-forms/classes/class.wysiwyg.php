<?php
require_once 'class.textarea.php';

/**
 * Description of class
 * 
 * @todo BUG Types needs to make right queueing adding T icon (Editor_addon)
 *
 * @author Srdjan
 */
class WPToolset_Field_Wysiwyg extends WPToolset_Field_Textarea
{

    protected $_settings = array('wp_version' => '3.3');

    public function metaform() {
        $form = array();
        $form[] = array(
            '#type' => 'markup',
            '#markup' => $this->getTitle() . $this->getDescription() . $this->_editor(),
        );
        return $form;
    }

    protected function _editor(){
        ob_start();
        wp_editor( $this->getValue(), $this->getId(),
                array(
            'wpautop' => true, // use wpautop?
            'media_buttons' => true, // show insert/upload button(s)
            'textarea_name' => $this->getName(), // set the textarea name to something different, square brackets [] can be used here
            'textarea_rows' => get_option( 'default_post_edit_rows', 10 ), // rows="..."
            'tabindex' => '',
            'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
            'editor_class' => 'wpt-wysiwyg', // add extra class(es) to the editor textarea
            'teeny' => false, // output the minimal editor config used in Press This
            'dfw' => false, // replace the default fullscreen with DFW (needs specific DOM elements and css)
            'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
            'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
        ) );
        return ob_get_clean() . "\n\n";
    }

}
