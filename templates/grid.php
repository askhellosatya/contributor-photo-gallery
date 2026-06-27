<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// expects $cpglry_photos, $cpglry_options (fallback cpglry_get_plugin_options), and $atts passed from renderer
if ( ! isset( $cpglry_options ) ) {
	$cpglry_options = cpglry_get_plugin_options();
}

if ( ! isset( $cpglry_photos ) || ! is_array( $cpglry_photos ) ) {
	$cpglry_photos = array();
}

// Resolve columns as integer (prioritize shortcode attribute then saved option)
$cpglry_columns = intval( $atts['columns'] ?? ( $cpglry_options['default_columns'] ?? 4 ) );
$cpglry_columns = max( 1, min( 6, $cpglry_columns ) ); // ensure within 1..6

$cpglry_card_style    = $cpglry_options['card_style'] ?? 'default';
$cpglry_show_captions = isset( $cpglry_options['show_captions'] ) ? ! empty( $cpglry_options['show_captions'] ) : true;
$cpglry_caption_color = isset( $cpglry_options['caption_text_color'] ) ? $cpglry_options['caption_text_color'] : '#0f1724';

// Build CSS custom properties safely
$cpglry_css_vars = array();

// Normalize hex color for comparison, simple lower-case trim helper
$cpglry_normalize_hex = function( $c ) {
	$c = trim( (string) $c );
	$c = strtolower( $c );
	// expand short hex like #fff to #ffffff if needed, or return as-is
	if ( preg_match( '/^#([0-9a-f]{3})$/', $c, $m ) ) {
		return '#' . $m[1][0] . $m[1][0] . $m[1][1] . $m[1][1] . $m[1][2] . $m[1][2];
	}
	return $c;
};

if ( ! empty( $cpglry_options['card_bg_color'] ) ) {
	$cpglry_bg = $cpglry_normalize_hex( $cpglry_options['card_bg_color'] );
	// treat '#ffffff' and '#fff' as default white, skip if white
	if ( $cpglry_bg !== '#ffffff' ) {
		$cpglry_css_vars[] = '--cpg-card-bg: ' . esc_attr( $cpglry_bg );
	}
}

if ( ! empty( $cpglry_options['card_border_style'] ) && $cpglry_options['card_border_style'] !== 'none' ) {
	$cpglry_border_width = intval( $cpglry_options['card_border_width'] ?? 1 );
	$cpglry_border_color = isset( $cpglry_options['card_border_color'] ) ? $cpglry_normalize_hex( $cpglry_options['card_border_color'] ) : '#e5e5e5';
	$cpglry_border_style = esc_attr( $cpglry_options['card_border_style'] );
	// Ensure a sane border width
	$cpglry_border_width = max( 0, min( 20, $cpglry_border_width ) );
	$cpglry_css_vars[]   = '--cpg-card-border: ' . esc_attr( $cpglry_border_width ) . 'px ' . $cpglry_border_style . ' ' . esc_attr( $cpglry_border_color );
}

// shadow presets
$cpglry_shadow_styles = array(
	'none'   => array( 'base' => 'none', 'hover' => 'none' ),
	'subtle' => array( 'base' => '0 1px 4px rgba(0,0,0,0.12)', 'hover' => '0 4px 12px rgba(0,0,0,0.16)' ),
	'medium' => array( 'base' => '0 4px 14px rgba(0,0,0,0.16)', 'hover' => '0 8px 22px rgba(0,0,0,0.22)' ),
	'strong' => array( 'base' => '0 10px 28px rgba(0,0,0,0.22)', 'hover' => '0 14px 36px rgba(0,0,0,0.28)' ),
);

if ( ! empty( $cpglry_options['card_shadow_style'] ) && isset( $cpglry_shadow_styles[ $cpglry_options['card_shadow_style'] ] ) ) {
	$cpglry_css_vars[] = '--cpg-card-shadow: ' . $cpglry_shadow_styles[ $cpglry_options['card_shadow_style'] ]['base'];
	$cpglry_css_vars[] = '--cpg-card-shadow-hover: ' . $cpglry_shadow_styles[ $cpglry_options['card_shadow_style'] ]['hover'];
}

if ( ! empty( $cpglry_caption_color ) ) {
	$cpglry_css_vars[] = '--cpg-caption-color: ' . esc_attr( $cpglry_caption_color );
}

// Build style value (not attribute)
$cpglry_style_value = '';
if ( ! empty( $cpglry_css_vars ) ) {
    $cpglry_style_value = implode( '; ', $cpglry_css_vars );
}

$cpglry_caption_class = ! $cpglry_show_captions ? ' cpg-no-captions' : '';

// echo container, escape style value at the point of output
echo '<div class="cpg-gallery-grid columns-' . esc_attr( $cpglry_columns ) . esc_attr( $cpglry_caption_class ) . '" data-cpg-columns="' . esc_attr( $cpglry_columns ) . '"'
    . ( $cpglry_style_value ? ' style="' . esc_attr( $cpglry_style_value ) . '"' : '' ) . '>';

foreach ( $cpglry_photos as $cpglry_photo ) {
	$cpglry_image_url = '';
	$cpglry_title     = '';
	$cpglry_link      = isset( $cpglry_photo['link'] ) ? $cpglry_photo['link'] : '';

	// robust extraction of featured media URL
	if ( isset( $cpglry_photo['_embedded']['wp:featuredmedia'][0]['media_details']['sizes'] ) ) {
		$cpglry_sizes = $cpglry_photo['_embedded']['wp:featuredmedia'][0]['media_details']['sizes'];
		// prefer 'large' then 'full' then first available
		if ( isset( $cpglry_sizes['large']['source_url'] ) ) {
			$cpglry_image_url = $cpglry_sizes['large']['source_url'];
		} elseif ( isset( $cpglry_sizes['full']['source_url'] ) ) {
			$cpglry_image_url = $cpglry_sizes['full']['source_url'];
		} else {
			// get any size
			$cpglry_first = reset( $cpglry_sizes );
			if ( isset( $cpglry_first['source_url'] ) ) {
				$cpglry_image_url = $cpglry_first['source_url'];
			}
		}
	}

	if ( isset( $cpglry_photo['content']['rendered'] ) ) {
		$cpglry_title = wp_strip_all_tags( $cpglry_photo['content']['rendered'] );
		$cpglry_title = mb_substr( $cpglry_title, 0, 30 ) . ( mb_strlen( $cpglry_title ) > 30 ? '...' : '' );
	}

	if ( $cpglry_image_url ) {
		$cpglry_open_in_new = isset( $cpglry_options['open_in_new_tab'] ) ? (bool) $cpglry_options['open_in_new_tab'] : true;
		$cpglry_target = $cpglry_open_in_new ? '_blank' : '';
		// include noreferrer as recommended when opening new tab
		$cpglry_rel = $cpglry_open_in_new ? 'noopener noreferrer' : '';

		echo '<div class="cpg-photo-card cpg-style-' . esc_attr( $cpglry_card_style ) . '">';
		echo '<a href="' . esc_url( $cpglry_link ) . '"' . ( $cpglry_target ? ' target="' . esc_attr( $cpglry_target ) . '"' : '' ) . ( $cpglry_rel ? ' rel="' . esc_attr( $cpglry_rel ) . '"' : '' ) . '>';
		echo '<div class="cpg-photo-image"><img src="' . esc_url( $cpglry_image_url ) . '" alt="' . esc_attr( $cpglry_title ) . '" loading="lazy"></div>';

		if ( $cpglry_show_captions && ! empty( $cpglry_title ) ) {
			echo '<div class="cpg-photo-content"><p style="color: var(--cpg-caption-color, ' . esc_attr( $cpglry_caption_color ) . ');">' . esc_html( $cpglry_title ) . '</p></div>';
		}

		echo '</a></div>';
	}
}

echo '</div>';
