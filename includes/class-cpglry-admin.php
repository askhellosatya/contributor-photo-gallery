<?php
/**
 * Admin class for Contributor Photo Gallery.
 *
 * @package ContributorPhotoGallery
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Admin class for Contributor Photo Gallery
 * - Registers settings, renders settings page, enqueues admin assets
 * - Shows a site-wide setup notice when plugin is not configured
 * - Shows a one-time settings-page "new shortcode" notice
 * - Provides AJAX handlers to persist dismissals
 */
class CPGLRY_Admin {

    private $options;

    public function __construct() {
        $this->options = cpglry_get_plugin_options();

        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );

        // enqueue on admin pages (we filter by hook inside the method)
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

        // Show site-wide setup notice (if plugin likely needs initial setup)
        add_action( 'admin_notices', array( $this, 'maybe_show_setup_notice' ) );

        // Show shortcode update notice on settings page
        add_action( 'admin_notices', array( $this, 'maybe_show_shortcode_notice' ) );

        // AJAX handlers for dismissible notices
        add_action( 'wp_ajax_cpglry_dismiss_new_shortcode_notice', array( $this, 'ajax_dismiss_new_shortcode_notice' ) );
        add_action( 'wp_ajax_cpglry_dismiss_setup_notice', array( $this, 'ajax_dismiss_setup_notice' ) );
        add_action( 'wp_ajax_cpglry_dismiss_shortcode_notice', array( $this, 'ajax_dismiss_shortcode_notice' ) );
    }

    /**
     * Register admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            esc_html__( 'Contributor Photo Gallery Settings', 'contributor-photo-gallery' ),
            esc_html__( 'Contributor Photo Gallery', 'contributor-photo-gallery' ),
            'manage_options',
            'contributor-photo-gallery',
            array( $this, 'settings_page' ),
            'dashicons-camera',
            58
        );

        // Add settings submenu
        add_submenu_page(
            'contributor-photo-gallery',
            esc_html__( 'Settings', 'contributor-photo-gallery' ),
            esc_html__( 'Settings', 'contributor-photo-gallery' ),
            'manage_options',
            'contributor-photo-gallery',
            array( $this, 'settings_page' )
        );

        // Add plugin action links
        add_filter( 'plugin_action_links_' . plugin_basename( CPGLRY_PLUGIN_PATH . 'contributor-photo-gallery.php' ), array( $this, 'add_plugin_action_links' ) );
    }
            'default_columns'     => array(
                'title'    => esc_html__( 'Grid Layout', 'contributor-photo-gallery' ),
                'callback' => array( $this, 'columns_field_callback' ),
                'section'  => 'cpglry_main',
            ),
            'card_style'          => array(
                'title'    => '',
                'callback' => array( $this, 'card_style_field_callback' ),
                'section'  => 'cpglry_styling',
            ),
            'card_bg_color'       => array(
                'title'    => '',
                'callback' => array( $this, 'card_bg_color_field_callback' ),
                'section'  => 'cpglry_styling',
            ),
            'card_border_style'   => array(
                'title'    => '',
                'callback' => array( $this, 'card_border_style_field_callback' ),
                'section'  => 'cpglry_styling',
            ),
            'card_shadow_style'   => array(
                'title'    => '',
                'callback' => array( $this, 'card_shadow_style_field_callback' ),
                'section'  => 'cpglry_styling',
            ),
            'show_captions'       => array(
                'title'    => '',
                'callback' => array( $this, 'show_captions_field_callback' ),
                'section'  => 'cpglry_styling',
            ),
            'caption_text_color'  => array(
                'title'    => '',
                'callback' => array( $this, 'caption_text_color_field_callback' ),
                'section'  => 'cpglry_styling',
            ),
            'cache_time'          => array(
                'title'    => esc_html__( 'Cache Duration', 'contributor-photo-gallery' ),
                'callback' => array( $this, 'cache_time_field_callback' ),
                'section'  => 'cpglry_advanced',
            ),
            'open_in_new_tab'     => array(
                'title'    => esc_html__( 'Link Behavior', 'contributor-photo-gallery' ),
                'callback' => array( $this, 'new_tab_field_callback' ),
                'section'  => 'cpglry_advanced',
            ),
            'enable_lazy_loading' => array(
                'title'    => esc_html__( 'Lazy Loading', 'contributor-photo-gallery' ),
                'callback' => array( $this, 'lazy_loading_field_callback' ),
                'section'  => 'cpglry_advanced',
            ),
        );

        foreach ( $fields as $id => $field ) {
            add_settings_field( $id, $field['title'], $field['callback'], 'contributor-photo-gallery', $field['section'] );
        }
    }

    /*
    -------------------------
        Section callbacks
        ------------------------- */
    public function settings_section_callback() {
        echo '<div class="cpg-section-intro"><p>' . esc_html__( 'Configure basic gallery display settings.', 'contributor-photo-gallery' ) . '</p></div>';
    }

    public function styling_settings_section_callback() {
        echo '<div class="cpg-section-intro"><p>' . esc_html__( 'Customize the visual appearance of photo cards.', 'contributor-photo-gallery' ) . '</p></div>';
    }

    public function advanced_settings_section_callback() {
        echo '<div class="cpg-section-intro"><p>' . esc_html__( 'Performance and behavior optimization settings.', 'contributor-photo-gallery' ) . '</p></div>';
    }

