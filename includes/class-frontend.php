<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPGLRY_Frontend {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
	}

	public function enqueue_frontend_assets() {
		// Enqueue the main frontend stylesheet
		wp_enqueue_style( 'cpg-frontend', CPGLRY_PLUGIN_URL . 'assets/css/frontend.css', array(), CPGLRY_VERSION );

		// Add small, critical inline CSS to guarantee columns behavior.
		// This ensures .cpg-gallery-grid.columns-N becomes a grid even if a theme
		// has conflicting rules. We keep rules minimal and scoped to plugin classes.
		$inline = '
            /* Contributor Photo Gallery - critical grid rules */
            .cpg-gallery-grid { display: grid; gap: 1rem; box-sizing: border-box; }
            .cpg-gallery-grid .cpg-photo-card { box-sizing: border-box; }
            .cpg-gallery-grid.columns-1 { grid-template-columns: repeat(1, 1fr); }
            .cpg-gallery-grid.columns-2 { grid-template-columns: repeat(2, 1fr); }
            .cpg-gallery-grid.columns-3 { grid-template-columns: repeat(3, 1fr); }
            .cpg-gallery-grid.columns-4 { grid-template-columns: repeat(4, 1fr); }
            .cpg-gallery-grid.columns-5 { grid-template-columns: repeat(5, 1fr); }
            .cpg-gallery-grid.columns-6 { grid-template-columns: repeat(6, 1fr); }

            /* More specific fallback in case theme uses generic image/grid rules */
            body .cpg-gallery-grid.columns-1 { display: grid !important; grid-template-columns: repeat(1,1fr) }
            body .cpg-gallery-grid.columns-2 { display: grid !important; grid-template-columns: repeat(2,1fr) }
            body .cpg-gallery-grid.columns-3 { display: grid !important; grid-template-columns: repeat(3,1fr) }
            body .cpg-gallery-grid.columns-4 { display: grid !important; grid-template-columns: repeat(4,1fr) }
            body .cpg-gallery-grid.columns-5 { display: grid !important; grid-template-columns: repeat(5,1fr) }
            body .cpg-gallery-grid.columns-6 { display: grid !important; grid-template-columns: repeat(6,1fr) }

            /* Small responsive fallback: ensure single column on very small screens */
            @media (max-width: 480px) {
                .cpg-gallery-grid.columns-2,
                .cpg-gallery-grid.columns-3,
                .cpg-gallery-grid.columns-4,
                .cpg-gallery-grid.columns-5,
                .cpg-gallery-grid.columns-6 {
                    grid-template-columns: repeat(1, 1fr) !important;
                }
            }
        ';
		wp_add_inline_style( 'cpg-frontend', $inline );
	}

	/**
	 * Static helper used by the global shortcode handler in the main plugin file.
	 * Renders the gallery using templates/grid.php which expects $cpglry_photos and $cpglry_options.
	 */
	public static function render_shortcode( $atts = array() ) {
		$cpglry_options = cpglry_get_plugin_options();

		// shortcode attrs override options
		$atts = shortcode_atts(
			array(
				'per_page' => $cpglry_options['default_per_page'],
				'columns'  => $cpglry_options['default_columns'],
				'user_id'  => $cpglry_options['default_user_id'],
			),
			$atts,
			'cp_gallery'
		);

		$user_id  = sanitize_text_field( $atts['user_id'] ?: $cpglry_options['default_user_id'] );
		$per_page = max( 1, min( 50, intval( $atts['per_page'] ) ) );
		// Ensure columns is a proper integer and in allowed range
		$columns = max( 1, min( 6, intval( $atts['columns'] ?? $cpglry_options['default_columns'] ) ) );

		if ( empty( $user_id ) ) {
			return '<div class="cpg-shortcode-error">Please configure a User ID in the plugin settings.</div>';
		}

		$cpglry_photos = CPGLRY_API::get_photos( $user_id, $per_page, $cpglry_options['cache_time'] );

		if ( is_wp_error( $cpglry_photos ) ) {
			return '<div class="cpg-shortcode-error">' . esc_html( $cpglry_photos->get_error_message() ) . '</div>';
		}

		// Ensure template receives the resolved $atts['columns'] as integer
		$atts['columns'] = $columns;

		// Load the grid template - it uses $cpglry_photos, $cpglry_options and $atts
		ob_start();
		include CPGLRY_PLUGIN_PATH . 'templates/grid.php';
		return ob_get_clean();
	}
}
