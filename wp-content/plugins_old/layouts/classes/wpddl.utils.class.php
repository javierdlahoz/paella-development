<?php

final class WPDD_Utils{

    public static final function assign_layout_to_post_object( $post_id, $layout_slug, $template = null, $meta = '' ){
        $ret = update_post_meta($post_id, WPDDL_LAYOUTS_META_KEY, $layout_slug, $meta);
        if( $ret && $template !== null ){
            update_post_meta($post_id, '_wp_page_template', $template);
        }
        return apply_filters('assign_layout_to_post_object', $ret, $post_id, $layout_slug, $template, $meta);
    }

    public static final function remove_layout_assignment_to_post_object( $post_id, $meta = '', $and_template = true ){
        $ret = delete_post_meta( $post_id, WPDDL_LAYOUTS_META_KEY, $meta );
        if( $ret && $and_template ){
            delete_post_meta($post_id, '_wp_page_template');
        }
        return apply_filters('remove_layout_assignment_to_post_object', $ret, $post_id, $meta, $and_template);
    }

    public static final function page_has_layout( $post_id )
    {
        $meta = get_post_meta( $post_id, WPDDL_LAYOUTS_META_KEY, true );

        if( $meta === '' ) {
            $ret = false;
        }
        elseif( $meta == '0' ){
            $ret = false;
        }
        else{
            $ret = $meta;
        }
        return $ret;
    }

    public static final function template_have_layout( $file )
    {

        $bool = false;

        $file_abs = get_template_directory() . '/' . $file;

        if ( file_exists( $file_abs ) ) {
            $file_data = @file_get_contents( $file_abs );
            if ($file_data === false) {
                // try child theme.
                $file_data = @file_get_contents(get_stylesheet_directory() . '/' . $file);
            }
            if ($file_data !== false) {
                if (strpos($file_data, 'the_ddlayout') !== false) {
                    $bool = true;
                }
            }
        }

        return $bool;
    }

    public static final function page_template_has_layout( $post_id )
    {
        $template = get_post_meta($post_id, '_wp_page_template', true);
        return self::template_have_layout( $template );
    }

    public static final function property_exists( $object, $property){
            return is_object( $object ) ? property_exists($object, $property) : false;
    }

    public static final function str_replace_once($str_pattern, $str_replacement, $string){

        if (strpos($string, $str_pattern) !== false){
            $occurrence = strpos($string, $str_pattern);
            return substr_replace($string, $str_replacement, $occurrence, strlen($str_pattern));
        }

        return $string;
    }

    public static function where( $array, $property, $value ){
        return array_filter($array, array( new Toolset_ArrayUtils($property, $value ), 'filter_array'));
    }

    public static function ajax_nonce_fail( $method ){
        return wp_json_encode( array('Data' => array( 'error' =>  __( sprintf('Nonce problem: apparently we do not know where the request comes from. %s', $method ), 'ddl-layouts') ) ) );
    }

    public static function ajax_caps_fail( $method ){
        return wp_json_encode( array( 'Data' => array( 'error' =>  __( sprintf('I am sorry but you don\'t have the necessary privileges to perform this action. %s', $method ), 'ddl-layouts') ) ) );
    }

    public static function user_not_admin(){
        return !current_user_can( DDL_CREATE );
    }

    public static function create_cell($name, $divider = 1, $cell_type = 'spacer')
    {
        // create most complex id possible
        $id = (string)uniqid('s', true);
        // het only the latest numeric only part
        $id = explode('.', $id);
        $id = "s" . $id[1];
        // keep only 5 chars to help base64_encode slowness
        $id = substr($id, 0, 5);
        // build a spacer and return it
        return (object)array(
            'name' => $name,
            'cell_type' => $cell_type,
            'row_divider' => $divider,
            'content' => '',
            'cssClass' => '',
            'cssId' => 'span1',
            'tag' => 'div',
            'width' => 1,
            'additionalCssClasses' => '',
            'editorVisualTemplateID' => '',
            'id' => $id,
            'kind' => 'Cell'
        );
    }


    public static function create_cells($amount, $divider = 1, $cell_type = 'spacer')
    {
        $spacers = array();
        for ($i = 0; $i < $amount; $i++) {
            $spacers[] = self::create_cell($i + 1, $divider, $cell_type);
        }
        return $spacers;
    }

    public static function is_post_published( $id ){
        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE ID = '%s' AND post_status = 'publish'", $id) ) > 0;
    }
}

class WPDDL_LayoutsCleaner
{
    private $layout_id;
    private $layout;
    private $cell_type;
    private $to_remove;
    private $removed;
    private $remapped = false;

    public function __construct($layout_id)
    {
        $this->remapped = false;
        $this->removed = array();
        $this->layout_id = $layout_id;
        $this->layout = WPDD_Layouts::get_layout_settings($this->layout_id, true);
    }

    public function remove_orphaned_ct_cells($cell_type, $property)
    {
        $this->remapped = false;
        $this->cell_type = $cell_type;
        $this->property = $property;
        $rows = $this->get_rows();
        $rows = $this->remap_rows($rows);

        if( null !== $rows ){
            $this->layout->Rows = $rows;
            WPDD_Layouts::save_layout_settings( $this->layout_id, $this->layout );
        }

        return $this->removed;
    }


    function remove_unwanted($row, $remove)
    {
        $this->to_remove = $remove;

        if (in_array($remove, $row->Cells)) {

            $width = $remove->width;
            $divider = $remove->row_divider;
            $index = array_search($remove, $row->Cells);
            $spacers = WPDD_Utils::create_cells($width, $divider);
            array_splice($row->Cells, $index, 1, $spacers);

        }

        return $row;
    }


    public function remap_rows( $rows )
    {
        foreach ($rows as $key => $row) {
            //$filtered = array_filter($row->Cells, array(&$this, 'filter_orphaned_cells_of_type'));
            if( !is_object($row) || property_exists($row, 'Cells') === false ){
                return null;
            }
            $filtered = $this->filtered_orphaned_cells_recurse( $row->Cells );
            if (empty($filtered) === false) {
                foreach ($filtered as $ret) {
                    $this->remapped = true;
                    $this->removed[] = $ret->name;
                    $rows[$key] = $this->remove_unwanted($row, $ret);
                }
            }
        }

        if ($this->remapped === true) {
            return $rows;
        }
        return null;
    }

    function filtered_orphaned_cells_recurse( $cells ){
            $array = array();
            foreach( $cells as $key => $cell ){
                if( is_object($cell) && $cell->kind === 'Container' ){
                    $container_rows = $this->remap_rows( $cell->Rows );
                    if( null !== $container_rows ){
                        $cell->Rows = $container_rows;
                    }
                } else if(
                    is_object($cell) &&
                    property_exists($cell, 'cell_type') &&
                    $cell->cell_type === $this->cell_type &&
                    $cell->content &&
                    $cell->content->{$this->property} &&
                    WPDD_Utils::is_post_published($cell->content->{$this->property}) === false
                ){
                    $array[] = $cell;
                }
            }

            return $array;
    }

    private function get_rows()
    {
        if( $this->layout && $this->layout->Rows ){
            return $this->layout->Rows;
        } else {
            return array();
        }

    }

    function filter_orphaned_cells_of_type($cell)
    {
        if (is_object($cell) && property_exists($cell, 'cell_type') && $cell->cell_type === $this->cell_type && $cell->content && $cell->content->{$this->property}) {
            return WPDD_Utils::is_post_published($cell->content->{$this->property}) === false;
        }
    }
}

class WPDDL_Layouts_WPML{

    private static $instance = null;
    static $languages = null;
    static $current_language = 'en';
    static $default_language = 'en';

    private function __construct(){

        self::$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=name&order=asc' );
        self::$current_language = apply_filters( 'wpml_current_language', NULL );
        self::$default_language = apply_filters('wpml_default_language', NULL );

        add_filter('assign_layout_to_post_object', array(&$this, 'handle_save_update_assignment'), 99, 5 );
        add_filter('remove_layout_assignment_to_post_object', array(&$this, 'handle_remove_assignment'), 99, 4 );

        add_action('ddl-add-wpml-custom-switcher', array(&$this, 'print_wpml_custom_switcher') );

        add_action('ddl-wpml-switch-language', array(&$this, 'ddl_wpml_switch_language'), 10, 1 );

        add_action( 'ddl-wpml-switcher-scripts', array(&$this, 'enqueue_language_switcher_script') );
    }

    public function ddl_wpml_switch_language( $lang ){
        self::$current_language = isset( $lang ) && $lang ? $lang :self::$default_language;
        do_action( 'wpml_switch_language', self::$current_language );
    }

    public function enqueue_language_switcher_script(){
        add_action('admin_print_scripts', array(&$this, 'enqueue_wpml_selector_script'));
    }

    function enqueue_wpml_selector_script(){

        if( null === self::wpml_languages() ) return;

        global $wpddlayout;

        $wpddlayout->enqueue_scripts('ddl-wpml-switcher');
        $wpddlayout->localize_script('ddl-wpml-switcher', 'DDLayout_LangSwitch_Settings', apply_filters( 'ddl-wpml-localize-switcher', array(
            'default_language' => self::$default_language
        ) ) );
    }

    public function print_wpml_custom_switcher(){
        $languages = self::wpml_languages();
        if( null === $languages ) return;

        ob_start();
        include_once WPDDL_GUI_ABSPATH . 'templates/layout-language-switcher.tpl.php';
        echo ob_get_clean();
    }

    public static function wpml_languages(){

        if( count(self::$languages) === 0 ) return null;

        return self::$languages;
    }

    public function handle_save_update_assignment(  $ret, $post_id, $layout_slug, $template, $meta ){
        if( $ret === false ) return $ret;

        $post_type = get_post_type( $post_id );
        $is_translated_post_type = apply_filters( 'wpml_is_translated_post_type', null, $post_type );
        if( $is_translated_post_type === false ){
            return $ret;
        }

        $translations  = apply_filters('wpml_content_translations', null, $post_id, $post_type);

        if( !$translations ){
            return $ret;
        }

        foreach( $translations as $translation){
            if( $translation->element_id !== $post_id ){
                $up = update_post_meta($translation->element_id, WPDDL_LAYOUTS_META_KEY, $layout_slug, $meta);
                if( $up && $template !== null ){
                    update_post_meta($translation->element_id, '_wp_page_template', $template);
                }
            }
        }

        return $ret;
    }

    public function handle_remove_assignment( $ret, $post_id, $meta, $and_template ){
        if( $ret === false ) return $ret;

        $post_type = get_post_type( $post_id );
        $is_translated_post_type = apply_filters( 'wpml_is_translated_post_type', null, $post_type );
        if( $is_translated_post_type === false ){
            return $ret;
        }
        $translations  = apply_filters('wpml_content_translations', null, $post_id, $post_type);

        if( !$translations ){
            return $ret;
        }

        foreach( $translations as $translation){

            if( $translation->element_id !== $post_id ){
                $up = delete_post_meta( $translation->element_id, WPDDL_LAYOUTS_META_KEY, $meta );
                if( $up && $and_template ){
                    delete_post_meta($translation->element_id, '_wp_page_template');
                }
            }
        }

        return $ret;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new WPDDL_Layouts_WPML();
        }

        return self::$instance;
    }
}
