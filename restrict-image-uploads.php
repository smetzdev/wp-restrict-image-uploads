<?php
/**
 * Plugin Name:     Restrict Image Uploads
 * Description:     Restricts image uploads to have a maxium width of 2560px and a maximum size of 1000kb.
 * Author:          Browsaar
 * Author URI:      https://browsaar.de
 * Text Domain:     browsaar_restrict_image_uploads
 * Domain Path:     /languages
 * Version:         0.1.0
 */
namespace Browsaar\RestrictImageUploads;

/**
 * Restrict image uploads to have a maxium width of 2560px and a maximum size of 1000kb.
 * Supposed to run as a prefilter for wp_handle_upload.
 *
 * @param array $file
 * @return void
 */
function restrict_image_size_and_dimensions( $file ) {
	$image_types = array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' );
	if ( in_array( $file['type'], $image_types ) ) {
		$image_size    = $file['size'];
		$maximum_size  = 1000 * 1024; // 1000 KB in bytes
		$image_info    = getimagesize( $file['tmp_name'] );
		$maximum_width = 2560;

		if ( $image_size > $maximum_size ) {
			$file['error'] = __( 'Images must not exceed the maximum size of 1000 KB.', 'browsaar_restrict_image_uploads' );
		}

		if ( $image_info[0] > $maximum_width ) {
			$file['error'] = __( 'Images must not exceed the maximum width of 2560px.', 'browsaar_restrict_image_uploads' );
		}
	}
	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'Browsaar\RestrictImageUploads\restrict_image_size_and_dimensions' );

add_action(
	'init',
	function () {
		// Load translations
		load_plugin_textdomain(
			'browsaar_restrict_image_uploads',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}
);
