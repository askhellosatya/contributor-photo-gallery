<?php
/**
 * Cache utilities for Contributor Photo Gallery.
 *
 * Handles clearing transient-based caches used by the plugin.
 *
 * @package ContributorPhotoGallery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPGLRY_Cache {

	public static function clear() {
		global $wpdb;

		// Use esc_like to build the LIKE pattern safely for the current DB prefix.
		$like = $wpdb->esc_like( '_transient_cpglry_photos_' ) . '%';

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$transient_names = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
				$like
			)
		);

		if ( empty( $transient_names ) ) {
			return;
		}

		foreach ( $transient_names as $transient_name ) {
			// Stored names are like "_transient_cpglry_photos_<hash>".
			$name = str_replace( '_transient_', '', $transient_name );
			delete_transient( $name );
		}
	}
}
