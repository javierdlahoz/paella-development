<?php

// The shortcode
function vsel_shortcode() {

$output = ""; 
$output .= '<div id="vsel">'; 

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; 
	$today = strtotime('today'); 

	$vsel_meta_query = array( 
		'relation' => 'AND',
		array( 
			'key' => 'event-date', 
			'value' => $today, 
			'compare' => '>=' 
		) 
	); 

	$vsel_query_args = array( 
		'post_type' => 'event', 
		'post_status' => 'publish', 
		'ignore_sticky_posts' => true, 
		'meta_key' => 'event-date', 
		'orderby' => 'meta_value_num', 
		'order' => 'asc',
 		'paged' => $paged, 
		'meta_query' => $vsel_meta_query,
	); 

	$vsel_events = new WP_Query( $vsel_query_args );

	if ( $vsel_events->have_posts() ) : 
		while( $vsel_events->have_posts() ): $vsel_events->the_post(); 
	
		$event_date = get_post_meta( get_the_ID(), 'event-date', true ); 
		$event_time = get_post_meta( get_the_ID(), 'event-time', true ); 
		$event_location = get_post_meta( get_the_ID(), 'event-location', true ); 
		$event_link = get_post_meta( get_the_ID(), 'event-link', true ); 

		// display the event list
		$output .= '<div class="vsel-content">'; 
			$output .= '<div class="vsel-meta">'; 
				$output .= '<h4>' . get_the_title() . '</h4>';
				$output .= '<p>';
				$output .= sprintf(__( 'Date: %s', 'eventlist' ), date_i18n( get_option( 'date_format' ), $event_date ) ); 
				$output .= '</p>';
				if(!empty($event_time)){
					$output .= '<p>';
					$output .= sprintf(__( 'Time: %s', 'eventlist' ), $event_time ); 
					$output .= '</p>';
				}
				if(!empty($event_location)){
					$output .= '<p>';
					$output .= sprintf(__( 'Location: %s', 'eventlist' ), $event_location ); 
					$output .= '</p>';
				}
				if(!empty($event_link)){
					$output .= '<p>';
					$output .= sprintf(__( 'More info: %s', 'eventlist' ), sprintf( '<a href="%1$s" target="_blank">%2$s</a>', $event_link, sprintf(__( 'click here', 'eventlist' ) ) ) ); 
					$output .= '</p>';
				}
			$output .= '</div>';
			$output .= '<div class="vsel-info">';
				if ( has_post_thumbnail() ) { 
					$output .=  get_the_post_thumbnail(); 
				} 
				$output .=  get_the_content();
			$output .= '</div>';
		$output .= '</div>';
	
		endwhile; 
	
		// pagination
		next_posts_link(  __( '&laquo; Next Events', 'eventlist' ), $vsel_events->max_num_pages ); 
		previous_posts_link( __( 'Previous Events &raquo;', 'eventlist' ) ); 

		wp_reset_postdata(); 

		else:
 
		$output .= '<p>';
		$output .= __('There are no upcoming events.', 'eventlist');
		$output .= '</p>';
	endif; 

$output .= '</div>';

return $output;

} 

add_shortcode('vsel', 'vsel_shortcode');

?>