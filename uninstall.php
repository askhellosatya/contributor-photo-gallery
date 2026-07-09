<?php
/**
 * Plugin uninstall cleanup for Contributor Photo Gallery.
 *
 * @package ContributorPhotoGallery
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove stored options on uninstall.
delete_option( 'cpglry_options' );
