<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$cpglry_options = cpglry_get_plugin_options();
?>
<div class="cpg-admin-container">
	<div class="cpg-header">
		<div>
			<h1>Contributor Photo Gallery</h1>
			<p>Showcase your photo contributions with elegance</p>
		</div>
		<div class="cpg-header-actions">
			<a href="https://wordpress.org/photos/submit/" target="_blank" class="cpg-btn-primary">Contribute Photos</a>
			<a href="https://github.com/askhellosatya/contributor-photo-gallery" target="_blank" class="cpg-btn-secondary">Documentation</a>
		</div>
	</div>
	<div class="cpg-main-content">
		<!-- LEFT PANEL: Settings -->
		<div class="cpg-settings-panel">
			<form method="post" action="options.php" class="cpg-form">
				<?php settings_fields( 'cpglry_settings' ); ?>

				<!-- Essential Configuration Card -->
				<div class="cpg-card">
					<div class="cpg-card-header">
						<h2>Essential Configuration</h2>
						<p>Configure your contribution display settings.</p>
					</div>
					<div class="cpg-card-content">
						<?php
						do_settings_fields( 'contributor-photo-gallery', 'cpglry_main' );
						?>
					</div>
				</div>

				<!-- Card Styling & Appearance Card -->
				<div class="cpg-card">
					<div class="cpg-card-header">
						<h2>Card Styling & Appearance</h2>
						<p>Customize the appearance and styling of your photo cards.</p>
					</div>
					<div class="cpg-card-content">
						<?php do_settings_fields( 'contributor-photo-gallery', 'cpglry_styling' ); ?>
					</div>
				</div>

				<!-- Display & Performance Card -->
				<div class="cpg-card">
					<div class="cpg-card-header">
						<h2>Display & Performance</h2>
						<p>Fine-tune performance and display preferences.</p>
					</div>
					<div class="cpg-card-content">
						<?php do_settings_fields( 'contributor-photo-gallery', 'cpglry_advanced' ); ?>
					</div>
				</div>

				<div class="cpg-form-footer">
					<button type="submit" class="cpg-btn-save">Save Settings</button>
				</div>
			</form>
		</div>

		<!-- RIGHT PANEL: Sidebar (preview, examples, tools) -->
		<div class="cpg-sidebar">
			<!-- Gallery Preview Card -->
			<?php if ( ! empty( $cpglry_options['default_user_id'] ) ) : ?>
				<div class="cpg-card cpg-preview-card">
					<div class="cpg-card-header">
						<h3>Gallery Preview</h3>
						<p>Sample with current settings</p>
					</div>
					<div class="cpg-card-content">
						<center>
							<div class="cpg-preview-wrapper" id="cpg-live-preview">
								<?php echo do_shortcode( '[cp_gallery per_page="1"]' ); ?>
							</div>
						</center>
						<div class="cpg-preview-note">
							<strong>Tip:</strong> Use <code>[cp_gallery]</code> in posts, pages, or widgets
						</div>
					</div>
				</div>
			<?php endif; ?>

			<!-- Usage Examples Card -->
			<div class="cpg-card">
				<div class="cpg-card-header">
					<h3>Usage Examples</h3>
					<p>Copy shortcodes for different use cases</p>
				</div>
				<div class="cpg-card-content">
					<?php
					$cpglry_examples = array(
						array( 'Portfolio Showcase', 'Professional portfolios', '[cp_gallery per_page="20" columns="4"]' ),
						array( 'About Page Integration', 'Personal branding', '[cp_gallery per_page="12" columns="3"]' ),
						array( 'Blog Enhancement', 'Content creation', '[cp_gallery per_page="6" columns="2"]' ),
						array( 'Sidebar Widget', 'Ongoing showcase', '[cp_gallery per_page="4" columns="1"]' ),
						array( 'Default Settings', 'Uses configured settings', '[cp_gallery]' ),
					);
					foreach ( $cpglry_examples as $cpglry_example ) :
						?>
						<div class="cpg-example-item">
							<div class="cpg-example-header">
								<strong><?php echo esc_html( $cpglry_example[0] ); ?></strong>
								<small><?php echo esc_html( $cpglry_example[1] ); ?></small>
							</div>
							<div class="cpg-code-block">
								<code><?php echo esc_html( $cpglry_example[2] ); ?></code>
								<button type="button" class="cpg-copy-btn" data-code="<?php echo esc_attr( $cpglry_example[2] ); ?>" aria-label="Copy shortcode to clipboard">Copy</button>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Tools & Resources Card -->
			<div class="cpg-card cpg-tools-card">
				<div class="cpg-card-header">
					<h3>Tools & Resources</h3>
				</div>
				<div class="cpg-card-content">
					<div id="wpcpg-admin-notices"></div>

					<div class="wpcpg-cache-card">
						<div class="wpcpg-cache-meta">
							<span class="wpcpg-cache-icon database-icon" aria-hidden="true"></span>
							<div class="wpcpg-cache-text">
								<strong>Clear Cache</strong>
								<span>Refresh photo data</span>
							</div>
						</div>
						<button type="button" class="wpcpg-clear-cache">Clear</button>
					</div>

					<a href="https://wordpress.org/photos/" target="_blank" class="cpg-tool-link">
						<span class="cpg-tool-icon">&#x1F4F7;</span>
						WordPress.org Photos Directory
					</a>
					<a href="https://github.com/askhellosatya/contributor-photo-gallery" target="_blank" class="cpg-tool-link">
						<span class="cpg-tool-icon">&#x1F4D6;</span>
						Plugin Documentation
					</a>
					<a href="https://wordpress.org/support/plugin/contributor-photo-gallery/" target="_blank" class="cpg-tool-link">
						<span class="cpg-tool-icon">&#x1F4AC;</span>
						Get Support
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
