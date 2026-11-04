<?php
/**
 * Helper functions for Contributor Photo Gallery.
 *
 * Provides default options and procedural compatibility wrappers.
 *
 * @package ContributorPhotoGallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default plugin options.
 */
function cpglry_get_default_options() {
	return array(
		'default_user_id'     => '',
		'default_per_page'    => 12,
		'default_columns'     => 4,
		'cache_time'          => 3600,
		'open_in_new_tab'     => 1,
		'enable_lazy_loading' => 1,
		// Card Styling
		'card_style'          => 'default',
		'card_bg_color'       => '#ffffff',
		'card_border_style'   => 'solid',
		'card_border_width'   => 1,
		'card_border_color'   => '#e5e5e5',
		'card_shadow_style'   => 'subtle',
		'show_captions'       => 1,
		// NEW: caption text color
		'caption_text_color'  => '#0f1724',
	);
}

/**
 * Get current saved options (merges with defaults)
 */
function cpglry_get_plugin_options() {
	$defaults = cpglry_get_default_options();
	$opts     = get_option( 'cpglry_options', array() );
	if ( ! is_array( $opts ) ) {
		$opts = array();
	}
	return wp_parse_args( $opts, $defaults );
}


/**
 * Backwards-compatible procedural wrapper for clearing photo cache.
 *
 * Some older code may call this function directly; it delegates to the
 * CPGLRY_Cache class implementation.
 */
if ( ! function_exists( 'cpglry_clear_photo_cache' ) ) {
	function cpglry_clear_photo_cache() {
		if ( class_exists( 'CPGLRY_Cache' ) && method_exists( 'CPGLRY_Cache', 'clear' ) ) {
			CPGLRY_Cache::clear();
		}
	}
}
