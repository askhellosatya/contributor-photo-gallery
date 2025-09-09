<?php
/**
 * Plugin Name: Contributor Photo Gallery
 * Plugin URI: https://wordpress.org/plugins/contributor-photo-gallery/  
 * Description: Showcase your contributions to WordPress.org/photos with elegant and responsive photo galleries.
 * Version: 2.5.1
 * Requires at least: 5.8
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * Author: Satyam Vishwakarma
 * Author URI: https://satyamvishwakarma.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: contributor-photo-gallery
 * Domain Path: /languages
 *
 * @package ContributorPhotoGallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CPGLRY_VERSION', '2.5.1' );
define( 'CPGLRY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'CPGLRY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/*
 * Includes
 */
require_once CPGLRY_PLUGIN_PATH . 'includes/helpers.php';
require_once CPGLRY_PLUGIN_PATH . 'includes/class-frontend.php';
require_once CPGLRY_PLUGIN_PATH . 'includes/class-admin.php';
require_once CPGLRY_PLUGIN_PATH . 'includes/class-cache.php';
require_once CPGLRY_PLUGIN_PATH . 'includes/class-api.php';

/*
 * Init
 */
new CPGLRY_Frontend();
new CPGLRY_Admin();

/*
 * Preserve legacy options on upgrade (safe migration)
 * Example: if older installs stored options under 'wpcontrib_options' we'll copy into 'cpglry_options'
 */
register_activation_hook(
	__FILE__,
	function () {
		// If new option already exists, nothing to do
		if ( get_option( 'cpglry_options' ) !== false ) {
			return;
		}

		// Try migrating from a common legacy option key
		$legacy_keys = array( 'wpcontrib_options', 'wpcontrib_photos_settings', 'contrib_photo_settings' );
		foreach ( $legacy_keys as $lk ) {
			$legacy = get_option( $lk );
			if ( ! empty( $legacy ) && is_array( $legacy ) ) {
				// merge with defaults to be safe
				$defaults = cpglry_get_default_options();
				$merged   = array_merge( $defaults, $legacy );
				update_option( 'cpglry_options', $merged );
				// keep the legacy option in place (do not delete automatically)
				return;
			}
		}

		add_action(
			'update_option_cpglry_options',
			function ( $old_value, $value, $option_name ) {
				// Only clear if values actually changed
				if ( $old_value === $value ) {
					return;
				}

				// If cpglry_Cache exists, call its clear method; otherwise, try to delete a known transient
				if ( class_exists( 'CPGLRY_Cache' ) && method_exists( 'CPGLRY_Cache', 'clear' ) ) {
					CPGLRY_Cache::clear();
				} else {
					// Fallback: remove a known plugin transient prefix if used
					global $wpdb;
					// Example: clear any transient beginning with 'cpglry_photos_'
                    $wpdb->query( // phpcs:ignore
						$wpdb->prepare(
							"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
							'_transient_cpglry_photos_%'
						)
					);
				}
			},
			10,
			3
		);

		// If nothing to migrate, ensure defaults exist
		add_option( 'cpglry_options', cpglry_get_default_options() );
	}
);

/*
 * AJAX handlers (preview + cache clear)
 * Handlers are defined here so scripts can call them; logic uses includes/class-api, templates, etc.
 */

add_action(
	'wp_ajax_wpcpglry_clear_cache',
	function () {
		check_ajax_referer( 'wpcpglry_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ), 403 );
		}

		if ( function_exists( 'cpglry_clear_photo_cache' ) ) {
			try {
				cpglry_clear_photo_cache();
				wp_send_json_success( array( 'message' => 'Cache cleared' ) );
			} catch ( Exception $e ) {
				wp_send_json_error( array( 'message' => 'Error clearing cache: ' . $e->getMessage() ), 500 );
			}
		} else {
			wp_send_json_success( array( 'message' => 'Cache cleared (no cache function found)' ) );
		}
	}
);

add_action(
	'wp_ajax_cpglry_refresh_preview',
	function () {

		check_ajax_referer( 'wpcpglry_admin_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ), 403 );
		}

		if ( empty( $_POST['settings'] ) ) {
			wp_send_json_error( '<div class="cpg-preview-error">No settings provided.</div>' );
			return;
		}

		$setting = sanitize_url( wp_unslash( $_POST['settings'] ) );
		parse_str( $setting, $form_data );
		$options = $form_data['cpglry_options'] ?? array();

		if ( empty( $options['default_user_id'] ) ) {
			wp_send_json_error( '<div class="cpg-preview-error">Please set a User ID first.</div>' );
			return;
		}

		// Normalize settings with defaults
		$defaults = cpglry_get_default_options();
		$options  = wp_parse_args( $options, $defaults );

		// sanitize a couple values we will use
		$user_id       = sanitize_text_field( $options['default_user_id'] );
		$show_captions = ! empty( $options['show_captions'] ) ? 1 : 0;
		$caption_color = sanitize_hex_color( $options['caption_text_color'] ?? $defaults['caption_text_color'] ) ?: $defaults['caption_text_color'];

		// Get one photo for preview
		$photos = cpglry_API::get_photos( $user_id, 1, 3600 );

		if ( is_wp_error( $photos ) ) {
			wp_send_json_error( '<div class="cpg-preview-error">' . esc_html( $photos->get_error_message() ) . '</div>' );
			return;
		}

		if ( empty( $photos ) ) {
			wp_send_json_error( '<div class="cpg-preview-error">No photos found for this user.</div>' );
			return;
		}

		ob_start();

		// Build CSS variables for dynamic styling
		$css_vars = array();
		if ( ! empty( $options['card_bg_color'] ) && $options['card_bg_color'] !== '#ffffff' ) {
			$css_vars[] = '--cpg-card-bg: ' . esc_attr( $options['card_bg_color'] );
		}

		if ( ! empty( $options['card_border_style'] ) && $options['card_border_style'] !== 'none' ) {
			$border_width = absint( $options['card_border_width'] ?? 1 );
			$border_color = sanitize_hex_color( $options['card_border_color'] ?? '#e5e5e5' ) ?: '#e5e5e5';
			$css_vars[]   = '--cpg-card-border: ' . esc_attr( $border_width ) . 'px ' . esc_attr( $options['card_border_style'] ) . ' ' . esc_attr( $border_color );
		}

		$shadow_map = array(
			'none'   => 'none',
			'subtle' => '0 1px 4px rgba(0,0,0,0.12)',
			'medium' => '0 4px 14px rgba(0,0,0,0.16)',
			'strong' => '0 10px 28px rgba(0,0,0,0.22)',
		);

		if ( ! empty( $options['card_shadow_style'] ) && isset( $shadow_map[ $options['card_shadow_style'] ] ) ) {
			$css_vars[] = '--cpg-card-shadow: ' . $shadow_map[ $options['card_shadow_style'] ];
		}

		$css_vars[] = '--cpg-caption-color: ' . esc_attr( $caption_color );

		$style_attr    = ! empty( $css_vars ) ? implode( '; ', $css_vars ) : '';
		$card_style    = $options['card_style'] ?? 'default';
		$caption_class = ! $show_captions ? ' cpg-no-captions' : '';

		$photo     = $photos[0];
		$image_url = $photo['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['large']['source_url'] ?? '';
		$title     = '';
		if ( ! empty( $photo['content']['rendered'] ) ) {
			$title = wp_strip_all_tags( $photo['content']['rendered'] );
			$title = mb_substr( $title, 0, 30 ) . ( mb_strlen( $title ) > 30 ? '...' : '' );
		}

		if ( $image_url ) {
			echo '<div class="cpg-gallery-grid cpg-preview-grid columns-1' . esc_attr( $caption_class ) . '" style="' . esc_attr( $style_attr ) . '">';
			echo '<div class="cpg-photo-card cpg-style-' . esc_attr( $card_style ) . '">';
			echo '<a href="javascript:void(0);">';
			echo '<div class="cpg-photo-image"><img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $title ) . '" loading="lazy"></div>';
			if ( $show_captions && ! empty( $title ) ) {
				echo '<div class="cpg-photo-content"><p style="color: var(--cpg-caption-color, ' . esc_attr( $caption_color ) . ');">' . esc_html( $title ) . '</p></div>';
			}
			echo '</a></div></div>';
		}

		$preview_html = ob_get_clean();
		wp_send_json_success( $preview_html );
	}
);

/*
 * Shortcode compatibility:
 * - New shortcode: [cp_gallery]
 * - Legacy shortcode preserved: [wpcontrib_photos] (calls the same handler)
 * Implementation: handler lives in CPGLRY_Frontend class for clean separation.
 */
if ( ! function_exists( 'cpglry_shortcode_handler' ) ) {
	function cpglry_shortcode_handler( $atts = array() ) {
		// Delegate to class frontend static helper
		if ( class_exists( 'CPGLRY_Frontend' ) && method_exists( 'CPGLRY_Frontend', 'render_shortcode' ) ) {
			return CPGLRY_Frontend::render_shortcode( $atts );
		}
		return '';
	}
}
add_shortcode( 'cp_gallery', 'cpglry_shortcode_handler' );
add_shortcode( 'wpcontrib_photos', 'cpglry_shortcode_handler' );
