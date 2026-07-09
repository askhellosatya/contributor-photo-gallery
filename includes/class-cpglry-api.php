<?php
/**
 * API helpers for Contributor Photo Gallery.
 *
 * Contains methods for fetching photos and resolving author IDs from WordPress.org/photos.
 *
 * @package ContributorPhotoGallery
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CPGLRY_API {

    public static function get_photos( $user_id, $per_page, $cache_time ) {
        $cache_key = 'cpglry_photos_' . md5( $user_id . '_' . $per_page );
        $photos    = get_transient( $cache_key );
        if ( false !== $photos ) {
            return $photos;
        }
        $api_url = add_query_arg(
            array(
                'author'   => $user_id,
                'per_page' => $per_page,
                '_embed'   => 'wp:featuredmedia',
            ),
            'https://wordpress.org/photos/wp-json/wp/v2/photos/'
        );

        $response = wp_safe_remote_get( $api_url, array( 'timeout' => 15 ) );
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        $body   = wp_remote_retrieve_body( $response );
        $photos = json_decode( $body, true );
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return new WP_Error( 'json_error', esc_html__( 'Invalid response from API', 'contributor-photo-gallery' ) );
        }

        set_transient( $cache_key, $photos, $cache_time );
        return $photos;
    }

    /**
     * Get WordPress.org photo directory user ID.
     *
     * @param string $username Username.
     * @return int|false
     */
    public static function get_photo_directory_user_id( $username ) {
        // Sanitize username and build a stable transient key.
        $username = sanitize_text_field( (string) $username );
        if ( $username === '' ) {
            return false;
        }

        $transient_key = 'cpglry_author_id_' . md5( $username );

        $user_id = get_transient( $transient_key );

        if ( false !== $user_id ) {
            return $user_id;
        }

        $url = 'https://wordpress.org/photos/author/' . rawurlencode( $username ) . '/';

        $response = wp_safe_remote_get( $url, array( 'timeout' => 15 ) );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $code = wp_remote_retrieve_response_code( $response );
        if ( 200 !== intval( $code ) ) {
            return false;
        }

        $content = wp_remote_retrieve_body( $response );
        if ( empty( $content ) || ! is_string( $content ) ) {
            return false;
        }

        // Look for author-<id> class anywhere in the returned HTML.
        if ( preg_match( '/author-(\d+)/i', $content, $matches ) ) {
            $found = absint( $matches[1] );
            if ( $found ) {
                set_transient( $transient_key, $found, WEEK_IN_SECONDS );
                return $found;
            }
        }

        return false;
    }
}
