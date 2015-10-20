<?php
/**
 * Plugin Name: Very Simple Event List
 * Description: This is a very simple plugin to display a list of your events. Use shortcode [vsel] to display your events on a page. For more info please check readme file.
 * Version: 2.2
 * Author: Guido van der Leest
 * Author URI: http://www.guidovanderleest.nl
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: eventlist
 * Domain Path: translation
 */


// load the plugin's text domain
function vsel_init() { 
	load_plugin_textdomain( 'eventlist', false, dirname( plugin_basename( __FILE__ ) ) . '/translation' );
}
add_action('plugins_loaded', 'vsel_init');


// enqueue plugin scripts
function vsel_frontend_scripts() {	
	if(!is_admin())	{
		wp_enqueue_style('vsel_style', plugins_url('vsel_style.css',__FILE__));
	}
}
add_action('wp_enqueue_scripts', 'vsel_frontend_scripts');


function vsel_enqueue_date_picker(){ 
	global $post_type; 
	if( 'event' != $post_type ) 
	return;
	wp_enqueue_script( 'vsel_datepicker_script', plugins_url( '/js/vsel_datepicker.js' , __FILE__ ), array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), '1.0', true ); 
	wp_enqueue_style('vsel_datepicker_style', plugins_url( '/css/vsel_datepicker.css',__FILE__));
}
add_action( 'admin_enqueue_scripts', 'vsel_enqueue_date_picker' ); 


// create the eventspage in dashboard
function vsel_custom_postype() { 
	$vsel_labels = array( 
		'name' => __( 'Events', 'eventlist' ), 
		'singular_name' => __( 'Event', 'eventlist' ), 
		'add_new' => __( 'Add New', 'eventlist' ), 
		'add_new_item' => __( 'Add New Event', 'eventlist' ), 
		'edit_item' => __( 'Edit Event', 'eventlist' ), 
		'new_item' => __( 'New Event', 'eventlist' ), 
		'view_item' => __( 'View Event', 'eventlist' ), 
		'search_items' => __( 'Search Events', 'eventlist' ), 
		'not_found' => __( 'No events found', 'eventlist' ), 
		'not_found_in_trash' => __( 'No events found in Trash', 'eventlist' ), 
	); 
	$vsel_args = array( 
		'label' => __( 'Events', 'eventlist' ), 
		'labels' => $vsel_labels, 
		'public' => true, 
		'can_export' => true, 
		'show_in_nav_menus' => false, 
		'show_ui' => true, 
		'capability_type' => 'post', 
		'supports'=> array('title', 'thumbnail', 'editor'), 
	); 
register_post_type( 'event', $vsel_args); 
}
add_action( 'init', 'vsel_custom_postype' ); 


// create a metabox with date, time and location
function vsel_metabox() { 
	add_meta_box( 
		'vsel-event-metabox', 
		__( 'Event Info', 'eventlist' ), 
		'vsel_metabox_callback', 
		'event', 
		'side', 
		'default' 
	); 
} 
add_action( 'add_meta_boxes', 'vsel_metabox' );


function vsel_metabox_callback( $post ) { 
	// generate a nonce field 
	wp_nonce_field( 'vsel_meta_box', 'vsel_nonce' ); 
	
	// get previously saved meta values (if any) 
	$event_date = get_post_meta( $post->ID, 'event-date', true ); 
	$event_time = get_post_meta( $post->ID, 'event-time', true ); 
	$event_location = get_post_meta( $post->ID, 'event-location', true ); 
	$event_link = get_post_meta( $post->ID, 'event-link', true ); 

	// if there is saved date retrieve it, else set it to the current time 
	$event_date = ! empty( $event_date ) ? $event_date : time(); 

	// metabox fields
	?> 
	<p><label for="vsel-date"><?php _e( 'Event Date', 'eventlist' ); ?></label> 
	<input class="widefat" id="vsel-date" type="text" name="vsel-date" required maxlength="10" placeholder="Date format: 20-10-2015" value="<?php echo date( 'd-m-Y', sanitize_text_field( $event_date ) ); ?>" /></p>
	<p><label for="vsel-time"><?php _e( 'Event Time', 'eventlist' ); ?></label> 
	<input class="widefat" id="vsel-time" type="text" name="vsel-time" maxlength="150" placeholder="Example: 16.00 - 18.00" value="<?php echo sanitize_text_field( $event_time ); ?>" /></p>
	<p><label for="vsel-location"><?php _e( 'Event Location', 'eventlist' ); ?></label> 
	<input class="widefat" id="vsel-location" type="text" name="vsel-location" maxlength="150" placeholder="Example: Times Square" value="<?php echo sanitize_text_field( $event_location ); ?>" /></p>
	<p><label for="vsel-link"><?php _e( 'Event URL', 'eventlist' ); ?></label> 
	<input class="widefat" id="vsel-link" type="text" name="vsel-link" maxlength="150" placeholder="Example: wordpress.org" value="<?php echo esc_url( $event_link ); ?>" /></p>
	<?php 
}


// save event
function vsel_save_event_info( $post_id ) { 
	// check if nonce is set
	if ( ! isset( $_POST['vsel_nonce'] ) ) {
		return;
	}
	// verify that nonce is valid
	if ( ! wp_verify_nonce( $_POST['vsel_nonce'], 'vsel_meta_box' ) ) {
		return;
	}
	// if this is an autosave, our form has not been submitted, so do nothing
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// check user permissions
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	// checking for the values and save fields 
	if ( isset( $_POST['vsel-date'] ) ) { 
		update_post_meta( $post_id, 'event-date', strtotime( $_POST['vsel-date'] ) ); 
	} 
	if ( isset( $_POST['vsel-time'] ) ) { 
		update_post_meta( $post_id, 'event-time', sanitize_text_field( $_POST['vsel-time'] ) ); 
	} 
	if ( isset( $_POST['vsel-location'] ) ) { 
		update_post_meta( $post_id, 'event-location', sanitize_text_field( $_POST['vsel-location'] ) ); 
	} 
	if ( isset( $_POST['vsel-link'] ) ) { 
		update_post_meta( $post_id, 'event-link', esc_url_raw( $_POST['vsel-link'] ) ); 
	} 
} 
add_action( 'save_post', 'vsel_save_event_info' );


// dashboard event columns
function vsel_custom_columns( $defaults ) { 
	unset( $defaults['date'] ); 
	$defaults['event_date'] = __( 'Event Date', 'eventlist' ); 
	$defaults['event_time'] = __( 'Event Time', 'eventlist' ); 
	$defaults['event_location'] = __( 'Event Location', 'eventlist' ); 
	return $defaults; 
} 
add_filter( 'manage_edit-event_columns', 'vsel_custom_columns', 10 );

function vsel_custom_columns_content( $column_name, $post_id ) { 
	if ( 'event_date' == $column_name ) { 
		$date = get_post_meta( $post_id, 'event-date', true ); 
		echo date_i18n( get_option( 'date_format' ), $date ); 

	} 
	if ( 'event_time' == $column_name ) { 
		$time = get_post_meta( $post_id, 'event-time', true ); 
		echo $time; 
	} 
	if ( 'event_location' == $column_name ) { 
		$location = get_post_meta( $post_id, 'event-location', true ); 
		echo $location; 
	} 
} 
add_action( 'manage_event_posts_custom_column', 'vsel_custom_columns_content', 10, 2 );


// add class to pagination
function vsel_prev_posts() { 
	return 'class="prev"'; 
} 
add_filter('previous_posts_link_attributes', 'vsel_prev_posts'); 

function vsel_next_posts() { 
	return 'class="next"'; 
}
add_filter('next_posts_link_attributes', 'vsel_next_posts'); 


// include the shortcode files
include 'vsel_shortcode.php';
include 'vsel_past_events_shortcode.php';

?>