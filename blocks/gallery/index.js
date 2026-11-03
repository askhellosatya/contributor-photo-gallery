/**
 * Contributor Photo Gallery block (editor script).
 *
 * Written without JSX so the plugin keeps working without a build step.
 * The block is dynamic: PHP renders it, and the editor shows a live
 * preview through ServerSideRender.
 */
( function ( wp, settings ) {
	if ( ! wp || ! wp.blocks || ! wp.element ) {
		return;
	}

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var __ = wp.i18n.__;
	var sprintf = wp.i18n.sprintf;
	var blockEditor = wp.blockEditor;
	var components = wp.components;
	var ServerSideRender = wp.serverSideRender;

	var defaults = settings || {};
	var defaultUserId = defaults.userId || '';
	var defaultPerPage = parseInt( defaults.perPage, 10 ) || 12;
	var defaultColumns = parseInt( defaults.columns, 10 ) || 4;

	function Placeholder( props ) {
		return el(
			components.Placeholder,
			{
				icon: 'format-gallery',
				label: __( 'Contributor Photo Gallery', 'contributor-photo-gallery' ),
				instructions: __(
					'Enter a WordPress.org username or numeric user ID to show their photo contributions.',
					'contributor-photo-gallery'
				),
			},
			el( components.TextControl, {
				label: __( 'WordPress.org username or user ID', 'contributor-photo-gallery' ),
				hideLabelFromVision: true,
				placeholder: __( 'e.g. makarandmane', 'contributor-photo-gallery' ),
				value: props.attributes.userId,
				onChange: function ( value ) {
					props.setAttributes( { userId: value.trim() } );
				},
			} ),
			props.settingsUrl &&
				el(
					components.ExternalLink,
					{ href: props.settingsUrl },
					__( 'Or set a default in plugin settings', 'contributor-photo-gallery' )
				)
		);
	}

	function Edit( props ) {
		var attributes = props.attributes;
		var setAttributes = props.setAttributes;
		var blockProps = blockEditor.useBlockProps();

		var effectiveUserId = attributes.userId || defaultUserId;
		var perPage = attributes.perPage || defaultPerPage;
		var columns = attributes.columns || defaultColumns;

		var inspector = el(
			blockEditor.InspectorControls,
			null,
			el(
				components.PanelBody,
				{ title: __( 'Gallery settings', 'contributor-photo-gallery' ), initialOpen: true },
				el( components.TextControl, {
					label: __( 'WordPress.org username or user ID', 'contributor-photo-gallery' ),
					help: defaultUserId
						? sprintf(
								/* translators: %s: default user from plugin settings. */
								__( 'Leave empty to use the default from settings (%s).', 'contributor-photo-gallery' ),
								defaultUserId
						  )
						: __( 'No default user is set in plugin settings.', 'contributor-photo-gallery' ),
					value: attributes.userId,
					onChange: function ( value ) {
						setAttributes( { userId: value.trim() } );
					},
				} ),
				el( components.RangeControl, {
					label: __( 'Number of photos', 'contributor-photo-gallery' ),
					value: perPage,
					min: 1,
					max: 50,
					onChange: function ( value ) {
						setAttributes( { perPage: value } );
					},
				} ),
				el( components.RangeControl, {
					label: __( 'Columns', 'contributor-photo-gallery' ),
					value: columns,
					min: 1,
					max: 6,
					onChange: function ( value ) {
						setAttributes( { columns: value } );
					},
				} ),
				defaults.settingsUrl &&
					el(
						'p',
						null,
						el(
							components.ExternalLink,
							{ href: defaults.settingsUrl },
							__( 'Card styles and colors', 'contributor-photo-gallery' )
						)
					)
			)
		);

		var body = effectiveUserId
			? el(
					components.Disabled,
					null,
					el( ServerSideRender, {
						block: props.name,
						attributes: attributes,
						skipBlockSupportAttributes: true,
					} )
			  )
			: el( Placeholder, {
					attributes: attributes,
					setAttributes: setAttributes,
					settingsUrl: defaults.settingsUrl,
			  } );

		return el( Fragment, null, inspector, el( 'div', blockProps, body ) );
	}

	wp.blocks.registerBlockType( 'contributor-photo-gallery/gallery', {
		edit: Edit,
		save: function () {
			return null;
		},
		transforms: {
			from: [
				{
					type: 'shortcode',
					tag: [ 'cp_gallery', 'wpcontrib_photos' ],
					attributes: {
						userId: {
							type: 'string',
							shortcode: function ( attrs ) {
								return attrs.named.user_id || '';
							},
						},
						perPage: {
							type: 'number',
							shortcode: function ( attrs ) {
								var n = parseInt( attrs.named.per_page, 10 );
								return isNaN( n ) ? undefined : n;
							},
						},
						columns: {
							type: 'number',
							shortcode: function ( attrs ) {
								var n = parseInt( attrs.named.columns, 10 );
								return isNaN( n ) ? undefined : n;
							},
						},
					},
				},
			],
		},
	} );
} )( window.wp, window.cpglryBlockSettings );
