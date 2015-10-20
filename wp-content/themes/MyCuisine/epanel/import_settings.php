<?php
add_action( 'admin_enqueue_scripts', 'import_epanel_javascript' );
function import_epanel_javascript( $hook_suffix ) {
	if ( 'admin.php' == $hook_suffix && isset( $_GET['import'] ) && isset( $_GET['step'] ) && 'wordpress' == $_GET['import'] && '1' == $_GET['step'] )
		add_action( 'admin_head', 'admin_headhook' );
}

function admin_headhook(){ ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$("p.submit").before("<p><input type='checkbox' id='importepanel' name='importepanel' value='1' style='margin-right: 5px;'><label for='importepanel'>Import epanel settings</label></p>");
		});
	</script>
<?php }

add_action('import_end','importend');
function importend(){
	global $wpdb, $shortname;

	#make custom fields image paths point to sampledata/sample_images folder
	$sample_images_postmeta = $wpdb->get_results(
		$wpdb->prepare( "SELECT meta_id, meta_value FROM $wpdb->postmeta WHERE meta_value REGEXP %s", 'http://et_sample_images.com' )
	);
	if ( $sample_images_postmeta ) {
		foreach ( $sample_images_postmeta as $postmeta ){
			$template_dir = get_template_directory_uri();
			if ( is_multisite() ){
				switch_to_blog(1);
				$main_siteurl = site_url();
				restore_current_blog();

				$template_dir = $main_siteurl . '/wp-content/themes/' . get_template();
			}
			preg_match( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $postmeta->meta_value, $matches );
			$image_path = $matches[1];

			$local_image = preg_replace( '/http:\/\/et_sample_images.com\/([^.]+).jpg/', $template_dir . '/sampledata/sample_images/$1.jpg', $postmeta->meta_value );

			$local_image = preg_replace( '/s:55:/', 's:' . strlen( $template_dir . '/sampledata/sample_images/' . $image_path . '.jpg' ) . ':', $local_image );

			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => esc_url_raw( $local_image ) ), array( 'meta_id' => $postmeta->meta_id ), array( '%s' ) );
		}
	}

	if ( !isset($_POST['importepanel']) )
		return;

	$importOptions = 'YToxMTE6e3M6MDoiIjtOO3M6MTQ6Im15Y3Vpc2luZV9sb2dvIjtzOjA6IiI7czoxNzoibXljdWlzaW5lX2Zhdmljb24iO3M6MDoiIjtzOjIyOiJteWN1aXNpbmVfY29sb3Jfc2NoZW1lIjtzOjc6IkRlZmF1bHQiO3M6MjA6Im15Y3Vpc2luZV9ibG9nX3N0eWxlIjtOO3M6MjA6Im15Y3Vpc2luZV9ncmFiX2ltYWdlIjtOO3M6MjY6Im15Y3Vpc2luZV9leGxjYXRzX21lbnVwYWdlIjthOjM6e2k6MDtzOjE6IjUiO2k6MTtzOjE6IjYiO2k6MjtzOjE6IjEiO31zOjIzOiJteWN1aXNpbmVfbWVudV9udW1wb3N0cyI7czoxOiIzIjtzOjIzOiJteWN1aXNpbmVfY3VycmVuY3lfc2lnbiI7czoxOiIkIjtzOjIyOiJteWN1aXNpbmVfY2F0bnVtX3Bvc3RzIjtzOjE6IjYiO3M6MjY6Im15Y3Vpc2luZV9hcmNoaXZlbnVtX3Bvc3RzIjtzOjE6IjUiO3M6MjU6Im15Y3Vpc2luZV9zZWFyY2hudW1fcG9zdHMiO3M6MToiNSI7czoyMjoibXljdWlzaW5lX3RhZ251bV9wb3N0cyI7czoxOiI1IjtzOjIxOiJteWN1aXNpbmVfZGF0ZV9mb3JtYXQiO3M6NjoiTSBqLCBZIjtzOjI5OiJteWN1aXNpbmVfY29tbWVudF9kYXRlX2Zvcm1hdCI7czo1OiJuLWotWSI7czoyMToibXljdWlzaW5lX3VzZV9leGNlcnB0IjtOO3M6MTU6Im15Y3Vpc2luZV9jdWZvbiI7czoyOiJvbiI7czoyNDoibXljdWlzaW5lX2Rpc3BsYXlfYmx1cmJzIjtzOjI6Im9uIjtzOjE1OiJteWN1aXNpbmVfcXVvdGUiO3M6Mjoib24iO3M6MTk6Im15Y3Vpc2luZV91c2VfYXJlYTEiO047czoxOToibXljdWlzaW5lX3VzZV9hcmVhMiI7TjtzOjE5OiJteWN1aXNpbmVfdXNlX2FyZWEzIjtOO3M6MzQ6Im15Y3Vpc2luZV9udW1wb3N0c19yZWNvbW1lbmRhdGlvbnMiO3M6MToiMyI7czozMzoibXljdWlzaW5lX2V4bGNhdHNfcmVjb21tZW5kYXRpb25zIjtOO3M6MTc6Im15Y3Vpc2luZV9hZGRyZXNzIjtzOjQwOiIxMDUxIE5pcG9tbyBTdCBTYW4gTHVpcyBPYmlzcG8sIENBIDkzNDAxIjtzOjE3OiJteWN1aXNpbmVfbW9uX2ZyaSI7czo3OiI4YW0tNnBtIjtzOjE3OiJteWN1aXNpbmVfc2F0X3N1biI7czo4OiI4YW0tMTBwbSI7czoxNToibXljdWlzaW5lX2VtYWlsIjtzOjA6IiI7czoxOToibXljdWlzaW5lX3RlbGVwaG9uZSI7czowOiIiO3M6MTM6Im15Y3Vpc2luZV9mYXgiO3M6MDoiIjtzOjIxOiJteWN1aXNpbmVfaG9tZV9wYWdlXzEiO3M6NToiQWJvdXQiO3M6MjE6Im15Y3Vpc2luZV9ob21lX3BhZ2VfMiI7czo1OiJBYm91dCI7czoyMToibXljdWlzaW5lX2hvbWVfcGFnZV8zIjtzOjU6IkFib3V0IjtzOjIxOiJteWN1aXNpbmVfcXVvdGVfbGluZTEiO3M6NzM6IuKAnFlvdXIgYXdlc29tZSBjb21wYW55IHNsb2dhbiBnb2VzIGhlcmUsIHdlIGhhdmUgdGhlIGJlc3QgZm9vZCBhcm91bmTigJ0iO3M6MjE6Im15Y3Vpc2luZV9xdW90ZV9saW5lMiI7czo3ODoiVW5jIGVsZW1lbnR1bSBsYWN1cyBpbiBncmF2aWRhIHBlbGxlbnRlc3F1ZSB1cm5hIGRvbG9yIGVsZWlmZW5kIGZlbGlzIGVsZWlmZW5kIjtzOjIzOiJteWN1aXNpbmVfbWVudV9wYWdlX3VybCI7czoxOiIjIjtzOjI0OiJteWN1aXNpbmVfaG9tZXBhZ2VfcG9zdHMiO3M6MToiNyI7czoyNDoibXljdWlzaW5lX2V4bGNhdHNfcmVjZW50IjtOO3M6MTg6Im15Y3Vpc2luZV9mZWF0dXJlZCI7czoyOiJvbiI7czoxOToibXljdWlzaW5lX2R1cGxpY2F0ZSI7czoyOiJvbiI7czoxODoibXljdWlzaW5lX2ZlYXRfY2F0IjtzOjQ6IkJsb2ciO3M6MjI6Im15Y3Vpc2luZV9mZWF0dXJlZF9udW0iO3M6MToiMyI7czoxOToibXljdWlzaW5lX3VzZV9wYWdlcyI7TjtzOjIwOiJteWN1aXNpbmVfZmVhdF9wYWdlcyI7TjtzOjIxOiJteWN1aXNpbmVfc2xpZGVyX2F1dG8iO047czoyNjoibXljdWlzaW5lX3NsaWRlcl9hdXRvc3BlZWQiO3M6NDoiNzAwMCI7czoxOToibXljdWlzaW5lX21lbnVwYWdlcyI7YToyOntpOjA7czozOiIyMzUiO2k6MTtzOjM6IjY2OCI7fXM6MjY6Im15Y3Vpc2luZV9lbmFibGVfZHJvcGRvd25zIjtzOjI6Im9uIjtzOjE5OiJteWN1aXNpbmVfaG9tZV9saW5rIjtzOjI6Im9uIjtzOjIwOiJteWN1aXNpbmVfc29ydF9wYWdlcyI7czoxMDoicG9zdF90aXRsZSI7czoyMDoibXljdWlzaW5lX29yZGVyX3BhZ2UiO3M6MzoiYXNjIjtzOjI3OiJteWN1aXNpbmVfdGllcnNfc2hvd25fcGFnZXMiO3M6MToiMyI7czoxODoibXljdWlzaW5lX21lbnVjYXRzIjthOjM6e2k6MDtzOjE6IjUiO2k6MTtzOjE6IjYiO2k6MjtzOjE6IjEiO31zOjM3OiJteWN1aXNpbmVfZW5hYmxlX2Ryb3Bkb3duc19jYXRlZ29yaWVzIjtzOjI6Im9uIjtzOjI2OiJteWN1aXNpbmVfY2F0ZWdvcmllc19lbXB0eSI7czoyOiJvbiI7czozMjoibXljdWlzaW5lX3RpZXJzX3Nob3duX2NhdGVnb3JpZXMiO3M6MToiMyI7czoxODoibXljdWlzaW5lX3NvcnRfY2F0IjtzOjQ6Im5hbWUiO3M6MTk6Im15Y3Vpc2luZV9vcmRlcl9jYXQiO3M6MzoiYXNjIjtzOjI1OiJteWN1aXNpbmVfZGlzYWJsZV90b3B0aWVyIjtOO3M6MTk6Im15Y3Vpc2luZV9wb3N0aW5mbzIiO2E6NDp7aTowO3M6NjoiYXV0aG9yIjtpOjE7czo0OiJkYXRlIjtpOjI7czoxMDoiY2F0ZWdvcmllcyI7aTozO3M6ODoiY29tbWVudHMiO31zOjIwOiJteWN1aXNpbmVfdGh1bWJuYWlscyI7czoyOiJvbiI7czoyNzoibXljdWlzaW5lX3Nob3dfcG9zdGNvbW1lbnRzIjtzOjI6Im9uIjtzOjI1OiJteWN1aXNpbmVfcGFnZV90aHVtYm5haWxzIjtOO3M6Mjg6Im15Y3Vpc2luZV9zaG93X3BhZ2VzY29tbWVudHMiO047czoxOToibXljdWlzaW5lX3Bvc3RpbmZvMSI7YTo0OntpOjA7czo2OiJhdXRob3IiO2k6MTtzOjQ6ImRhdGUiO2k6MjtzOjEwOiJjYXRlZ29yaWVzIjtpOjM7czo4OiJjb21tZW50cyI7fXM6MjY6Im15Y3Vpc2luZV90aHVtYm5haWxzX2luZGV4IjtzOjI6Im9uIjtzOjIzOiJteWN1aXNpbmVfY3VzdG9tX2NvbG9ycyI7TjtzOjE5OiJteWN1aXNpbmVfY2hpbGRfY3NzIjtOO3M6MjI6Im15Y3Vpc2luZV9jaGlsZF9jc3N1cmwiO3M6MDoiIjtzOjI0OiJteWN1aXNpbmVfY29sb3JfbWFpbmZvbnQiO3M6MDoiIjtzOjI0OiJteWN1aXNpbmVfY29sb3JfbWFpbmxpbmsiO3M6MDoiIjtzOjI0OiJteWN1aXNpbmVfY29sb3JfcGFnZWxpbmsiO3M6MDoiIjtzOjMxOiJteWN1aXNpbmVfY29sb3JfcGFnZWxpbmtfYWN0aXZlIjtzOjA6IiI7czoyNDoibXljdWlzaW5lX2NvbG9yX2hlYWRpbmdzIjtzOjA6IiI7czoyOToibXljdWlzaW5lX2NvbG9yX3NpZGViYXJfbGlua3MiO3M6MDoiIjtzOjIxOiJteWN1aXNpbmVfZm9vdGVyX3RleHQiO3M6MDoiIjtzOjI3OiJteWN1aXNpbmVfY29sb3JfZm9vdGVybGlua3MiO3M6MDoiIjtzOjI0OiJteWN1aXNpbmVfc2VvX2hvbWVfdGl0bGUiO047czozMDoibXljdWlzaW5lX3Nlb19ob21lX2Rlc2NyaXB0aW9uIjtOO3M6Mjc6Im15Y3Vpc2luZV9zZW9faG9tZV9rZXl3b3JkcyI7TjtzOjI4OiJteWN1aXNpbmVfc2VvX2hvbWVfY2Fub25pY2FsIjtOO3M6Mjg6Im15Y3Vpc2luZV9zZW9faG9tZV90aXRsZXRleHQiO3M6MDoiIjtzOjM0OiJteWN1aXNpbmVfc2VvX2hvbWVfZGVzY3JpcHRpb250ZXh0IjtzOjA6IiI7czozMToibXljdWlzaW5lX3Nlb19ob21lX2tleXdvcmRzdGV4dCI7czowOiIiO3M6MjM6Im15Y3Vpc2luZV9zZW9faG9tZV90eXBlIjtzOjI3OiJCbG9nTmFtZSB8IEJsb2cgZGVzY3JpcHRpb24iO3M6Mjc6Im15Y3Vpc2luZV9zZW9faG9tZV9zZXBhcmF0ZSI7czozOiIgfCAiO3M6MjY6Im15Y3Vpc2luZV9zZW9fc2luZ2xlX3RpdGxlIjtOO3M6MzI6Im15Y3Vpc2luZV9zZW9fc2luZ2xlX2Rlc2NyaXB0aW9uIjtOO3M6Mjk6Im15Y3Vpc2luZV9zZW9fc2luZ2xlX2tleXdvcmRzIjtOO3M6MzA6Im15Y3Vpc2luZV9zZW9fc2luZ2xlX2Nhbm9uaWNhbCI7TjtzOjMyOiJteWN1aXNpbmVfc2VvX3NpbmdsZV9maWVsZF90aXRsZSI7czo5OiJzZW9fdGl0bGUiO3M6Mzg6Im15Y3Vpc2luZV9zZW9fc2luZ2xlX2ZpZWxkX2Rlc2NyaXB0aW9uIjtzOjE1OiJzZW9fZGVzY3JpcHRpb24iO3M6MzU6Im15Y3Vpc2luZV9zZW9fc2luZ2xlX2ZpZWxkX2tleXdvcmRzIjtzOjEyOiJzZW9fa2V5d29yZHMiO3M6MjU6Im15Y3Vpc2luZV9zZW9fc2luZ2xlX3R5cGUiO3M6MjE6IlBvc3QgdGl0bGUgfCBCbG9nTmFtZSI7czoyOToibXljdWlzaW5lX3Nlb19zaW5nbGVfc2VwYXJhdGUiO3M6MzoiIHwgIjtzOjI5OiJteWN1aXNpbmVfc2VvX2luZGV4X2Nhbm9uaWNhbCI7TjtzOjMxOiJteWN1aXNpbmVfc2VvX2luZGV4X2Rlc2NyaXB0aW9uIjtOO3M6MjQ6Im15Y3Vpc2luZV9zZW9faW5kZXhfdHlwZSI7czoyNDoiQ2F0ZWdvcnkgbmFtZSB8IEJsb2dOYW1lIjtzOjI4OiJteWN1aXNpbmVfc2VvX2luZGV4X3NlcGFyYXRlIjtzOjM6IiB8ICI7czozMzoibXljdWlzaW5lX2ludGVncmF0ZV9oZWFkZXJfZW5hYmxlIjtzOjI6Im9uIjtzOjMxOiJteWN1aXNpbmVfaW50ZWdyYXRlX2JvZHlfZW5hYmxlIjtzOjI6Im9uIjtzOjM2OiJteWN1aXNpbmVfaW50ZWdyYXRlX3NpbmdsZXRvcF9lbmFibGUiO3M6Mjoib24iO3M6Mzk6Im15Y3Vpc2luZV9pbnRlZ3JhdGVfc2luZ2xlYm90dG9tX2VuYWJsZSI7czoyOiJvbiI7czoyNjoibXljdWlzaW5lX2ludGVncmF0aW9uX2hlYWQiO3M6MDoiIjtzOjI2OiJteWN1aXNpbmVfaW50ZWdyYXRpb25fYm9keSI7czowOiIiO3M6MzI6Im15Y3Vpc2luZV9pbnRlZ3JhdGlvbl9zaW5nbGVfdG9wIjtzOjA6IiI7czozNToibXljdWlzaW5lX2ludGVncmF0aW9uX3NpbmdsZV9ib3R0b20iO3M6MDoiIjtzOjIwOiJteWN1aXNpbmVfNDY4X2VuYWJsZSI7TjtzOjE5OiJteWN1aXNpbmVfNDY4X2ltYWdlIjtzOjA6IiI7czoxNzoibXljdWlzaW5lXzQ2OF91cmwiO3M6MDoiIjtzOjIxOiJteWN1aXNpbmVfNDY4X2Fkc2Vuc2UiO3M6MDoiIjt9';

	/*global $options;

	foreach ($options as $value) {
		if( isset( $value['id'] ) ) {
			update_option( $value['id'], $value['std'] );
		}
	}*/

	$importedOptions = unserialize(base64_decode($importOptions));

	foreach ($importedOptions as $key=>$value) {
		if ($value != '') update_option( $key, $value );
	}

	update_option( $shortname . '_use_pages', 'false' );
} ?>