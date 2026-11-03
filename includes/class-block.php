<?php
/**
 * Gutenberg block: Contributor Photo Gallery.
 *
 * A dynamic block that reuses the shortcode renderer, so the block, the
 * [cp_gallery] shortcode and the legacy [wpcontrib_photos] shortcode always
 * produce the same markup and use the same settings.
 *
 * @package Contributor_Photo_Gallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPGLRY_Block {

	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register assets and the block type.
	 */
	public function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Frontend stylesheet, shared with the shortcode. Registering it here lets
		// block.json load it in the editor preview as well.
		if ( ! wp_style_is( 'cpg-frontend', 'registered' ) ) {
			wp_register_style( 'cpg-frontend', CPGLRY_PLUGIN_URL . 'assets/css/frontend.css', array(), CPGLRY_VERSION );
		}
		if ( ! wp_styles()->get_data( 'cpg-frontend', 'after' ) ) {
			wp_add_inline_style( 'cpg-frontend', CPGLRY_Frontend::get_critical_css() );
		}

		wp_register_script(
			'cpglry-block-editor',
			CPGLRY_PLUGIN_URL . 'blocks/gallery/index.js',
			array( 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
			CPGLRY_VERSION,
			true
		);

		$options = cpglry_get_plugin_options();
		wp_localize_script(
			'cpglry-block-editor',
			'cpglryBlockSettings',
			array(
				'userId'      => (string) $options['default_user_id'],
				'perPage'     => (int) $options['default_per_page'],
				'columns'     => (int) $options['default_columns'],
				'settingsUrl' => current_user_can( 'manage_options' ) ? admin_url( 'admin.php?page=contributor-photo-gallery' ) : '',
			)
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'cpglry-block-editor', 'contributor-photo-gallery' );
		}

		register_block_type(
			CPGLRY_PLUGIN_PATH . 'blocks/gallery',
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * Render the block on the frontend and in the editor preview.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function render( $attributes ) {
		$atts = array();

		if ( ! empty( $attributes['userId'] ) ) {
			$user = sanitize_text_field( $attributes['userId'] );

			// The API needs a numeric ID; resolve WordPress.org usernames the same
			// way the settings screen does (the result is cached in a transient).
			if ( ! ctype_digit( $user ) ) {
				$resolved = CPGLRY_API::get_photo_directory_user_id( strtolower( $user ) );
				if ( empty( $resolved ) || is_wp_error( $resolved ) ) {
					return sprintf(
						'<div %1$s><div class="cpg-shortcode-error">%2$s</div></div>',
						$this->wrapper_attributes(),
						esc_html__( 'Could not find that WordPress.org username. Check the spelling or use the numeric user ID.', 'contributor-photo-gallery' )
					);
				}
				$user = (string) $resolved;
			}

			$atts['user_id'] = $user;
		}
		if ( ! empty( $attributes['perPage'] ) ) {
			$atts['per_page'] = absint( $attributes['perPage'] );
		}
		if ( ! empty( $attributes['columns'] ) ) {
			$atts['columns'] = absint( $attributes['columns'] );
		}

		$html = CPGLRY_Frontend::render_shortcode( $atts );

		return sprintf( '<div %1$s>%2$s</div>', $this->wrapper_attributes(), $html );
	}

	/**
	 * Wrapper attributes, including alignment and spacing from block supports.
	 *
	 * @return string
	 */
	private function wrapper_attributes() {
		return function_exists( 'get_block_wrapper_attributes' )
			? get_block_wrapper_attributes( array( 'class' => 'cpg-block' ) )
			: 'class="cpg-block"';
	}
}
