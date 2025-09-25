<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// expects $photos, $options (fallback cpglry_get_plugin_options), and $atts passed from renderer
if ( ! isset( $options ) ) {
	$options = cpglry_get_plugin_options();
}
if ( ! isset( $photos ) || ! is_array( $photos ) ) {
	$photos = array();
}

// Resolve columns as integer (prioritize shortcode attribute then saved option)
$columns = intval( $atts['columns'] ?? ( $options['default_columns'] ?? 4 ) );
$columns = max( 1, min( 6, $columns ) ); // ensure within 1..6

$card_style    = $options['card_style'] ?? 'default';
$show_captions = isset( $options['show_captions'] ) ? ! empty( $options['show_captions'] ) : true;
$caption_color = isset( $options['caption_text_color'] ) ? $options['caption_text_color'] : '#0f1724';

// Build CSS custom properties safely
$css_vars = array();

// Normalize hex color for comparison, simple lower-case trim helper
$normalize_hex = function( $c ) {
	$c = trim( (string) $c );
	$c = strtolower( $c );
	// expand short hex like #fff to #ffffff if needed, or return as-is
	if ( preg_match( '/^#([0-9a-f]{3})$/', $c, $m ) ) {
		return '#' . $m[1][0] . $m[1][0] . $m[1][1] . $m[1][1] . $m[1][2] . $m[1][2];
	}
	return $c;
};

if ( ! empty( $options['card_bg_color'] ) ) {
	$bg = $normalize_hex( $options['card_bg_color'] );
	// treat '#ffffff' and '#fff' as default white, skip if white
	if ( $bg !== '#ffffff' ) {
		$css_vars[] = '--cpg-card-bg: ' . esc_attr( $bg );
	}
}

if ( ! empty( $options['card_border_style'] ) && $options['card_border_style'] !== 'none' ) {
	$border_width = intval( $options['card_border_width'] ?? 1 );
	$border_color = isset( $options['card_border_color'] ) ? $normalize_hex( $options['card_border_color'] ) : '#e5e5e5';
	$border_style = esc_attr( $options['card_border_style'] );
	// Ensure a sane border width
	$border_width = max( 0, min( 20, $border_width ) );
	$css_vars[]   = '--cpg-card-border: ' . esc_attr( $border_width ) . 'px ' . $border_style . ' ' . esc_attr( $border_color );
}

// shadow presets
$shadow_styles = array(
	'none'   => array( 'base' => 'none', 'hover' => 'none' ),
	'subtle' => array( 'base' => '0 1px 4px rgba(0,0,0,0.12)', 'hover' => '0 4px 12px rgba(0,0,0,0.16)' ),
	'medium' => array( 'base' => '0 4px 14px rgba(0,0,0,0.16)', 'hover' => '0 8px 22px rgba(0,0,0,0.22)' ),
	'strong' => array( 'base' => '0 10px 28px rgba(0,0,0,0.22)', 'hover' => '0 14px 36px rgba(0,0,0,0.28)' ),
);

if ( ! empty( $options['card_shadow_style'] ) && isset( $shadow_styles[ $options['card_shadow_style'] ] ) ) {
	$css_vars[] = '--cpg-card-shadow: ' . $shadow_styles[ $options['card_shadow_style'] ]['base'];
	$css_vars[] = '--cpg-card-shadow-hover: ' . $shadow_styles[ $options['card_shadow_style'] ]['hover'];
}

if ( ! empty( $caption_color ) ) {
	$css_vars[] = '--cpg-caption-color: ' . esc_attr( $caption_color );
}

// Build style value (not attribute)
$style_value = '';
if ( ! empty( $css_vars ) ) {
    $style_value = implode( '; ', $css_vars );
}

$caption_class = ! $show_captions ? ' cpg-no-captions' : '';

// echo container, escape style value at the point of output
echo '<div class="cpg-gallery-grid columns-' . esc_attr( $columns ) . esc_attr( $caption_class ) . '" data-cpg-columns="' . esc_attr( $columns ) . '"'
    . ( $style_value ? ' style="' . esc_attr( $style_value ) . '"' : '' ) . '>';

foreach ( $photos as $photo ) {
	$image_url = '';
	$title     = '';
	$link      = isset( $photo['link'] ) ? $photo['link'] : '';

	// robust extraction of featured media URL
	if ( isset( $photo['_embedded']['wp:featuredmedia'][0]['media_details']['sizes'] ) ) {
		$sizes = $photo['_embedded']['wp:featuredmedia'][0]['media_details']['sizes'];
		// prefer 'large' then 'full' then first available
		if ( isset( $sizes['large']['source_url'] ) ) {
			$image_url = $sizes['large']['source_url'];
		} elseif ( isset( $sizes['full']['source_url'] ) ) {
			$image_url = $sizes['full']['source_url'];
		} else {
			// get any size
			$first = reset( $sizes );
			if ( isset( $first['source_url'] ) ) {
				$image_url = $first['source_url'];
			}
		}
	}

	if ( isset( $photo['content']['rendered'] ) ) {
		$title = wp_strip_all_tags( $photo['content']['rendered'] );
		$title = mb_substr( $title, 0, 30 ) . ( mb_strlen( $title ) > 30 ? '...' : '' );
	}

	if ( $image_url ) {
		$open_in_new = isset( $options['open_in_new_tab'] ) ? (bool) $options['open_in_new_tab'] : true;
		$target = $open_in_new ? '_blank' : '';
		// include noreferrer as recommended when opening new tab
		$rel = $open_in_new ? 'noopener noreferrer' : '';

		echo '<div class="cpg-photo-card cpg-style-' . esc_attr( $card_style ) . '">';
		echo '<a href="' . esc_url( $link ) . '"' . ( $target ? ' target="' . esc_attr( $target ) . '"' : '' ) . ( $rel ? ' rel="' . esc_attr( $rel ) . '"' : '' ) . '>';
		echo '<div class="cpg-photo-image"><img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $title ) . '" loading="lazy"></div>';

		if ( $show_captions && ! empty( $title ) ) {
			echo '<div class="cpg-photo-content"><p style="color: var(--cpg-caption-color, ' . esc_attr( $caption_color ) . ');">' . esc_html( $title ) . '</p></div>';
		}

		echo '</a></div>';
	}
}

echo '</div>';
