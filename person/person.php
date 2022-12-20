<?php

/**
 * @class FLPersonModule
 */
class FLPersonModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Person', 'fl-builder' ),
			'description'     => __( 'A person card to display staff information.', 'fl-builder' ),
			'category'        => __( 'Media', 'fl-builder' ),
			'partial_refresh' => true,
			'icon'            => 'megaphone.svg',
		));
	}

	/**
	 * Ensure backwards compatibility with old settings.
	 *
	 * @since 2.2
	 * @param object $settings A module settings object.
	 * @param object $helper A settings compatibility helper.
	 * @return object
	 */
	public function filter_settings( $settings, $helper ) {

		// Make sure we have a typography array.
		if ( ! isset( $settings->typography ) || ! is_array( $settings->typography ) ) {
			$settings->typography = array();
		}

		// Handle old title size settings.
		if ( isset( $settings->title_size ) && 'custom' === $settings->title_size ) {
			$settings->typography['font_size'] = array(
				'length' => $settings->title_custom_size,
				'unit'   => 'px',
			);
			unset( $settings->title_size );
			unset( $settings->title_custom_size );
		}

		// Handle old button module settings.
		$helper->filter_child_module_settings( 'button', $settings, array(
			'btn_3d'                 => 'three_d',
			'btn_style'              => 'style',
			'btn_padding'            => 'padding',
			'btn_padding_top'        => 'padding_top',
			'btn_padding_bottom'     => 'padding_bottom',
			'btn_padding_left'       => 'padding_left',
			'btn_padding_right'      => 'padding_right',
			'btn_mobile_align'       => 'mobile_align',
			'btn_align_responsive'   => 'align_responsive',
			'btn_font_size'          => 'font_size',
			'btn_font_size_unit'     => 'font_size_unit',
			'btn_typography'         => 'typography',
			'btn_bg_color'           => 'bg_color',
			'btn_bg_hover_color'     => 'bg_hover_color',
			'btn_bg_opacity'         => 'bg_opacity',
			'btn_bg_hover_opacity'   => 'bg_hover_opacity',
			'btn_border'             => 'border',
			'btn_border_hover_color' => 'border_hover_color',
			'btn_border_radius'      => 'border_radius',
			'btn_border_size'        => 'border_size',
		) );

		// Check if Advanced Gradient is to be applied.
		if ( ! empty( $settings->style ) && 'gradient' === $settings->style ) {
			if ( ! empty( $settings->bg_gradient['colors'][0] ) || ! empty( $settings->bg_gradient['colors'][1] ) ) {
				$settings->style = 'adv-gradient';
			}
		}

		// Return the filtered settings.
		return $settings;
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings ) {
		// Cache the photo data.
		$settings->photo_data = FLBuilderPhoto::get_attachment_data( $settings->photo );

		return $settings;
	}

	/**
	 * @method delete
	 */
	public function delete() {
		// Delete photo module cache.
		if ( 'photo' == $this->settings->image_type && ! empty( $this->settings->photo_src ) ) {
			$module_class                         = get_class( FLBuilderModel::$modules['photo'] );
			$photo_module                         = new $module_class();
			$photo_module->settings               = new stdClass();
			$photo_module->settings->photo_source = 'library';
			$photo_module->settings->photo_src    = $this->settings->photo_src;
			$photo_module->settings->crop         = $this->settings->photo_crop;
			$photo_module->delete();
		}
	}

	/**
	 * @method get_classname
	 */
	public function get_classname() {
		$classname = 'fl-person';

		if ( 'photo' == $this->settings->image_type ) {
			$classname .= ' fl-person-has-photo fl-person-photo-' . $this->settings->photo_position;
		} 

		return $classname;
	}


	/**
	 * @method render_name
	 */
	public function render_name() {
		echo '<h3 class="fl-person-name">';
		echo $this->settings->name;
		echo '</h3>';
	}
	
	/**
	 * @method render_title
	 */
	public function render_title() {
		if ( $this->settings->title ) {	
	
			echo '<h4 class="fl-person-title">';
			echo $this->settings->title;
			echo '</h4>';
			
		}
	}
	
	/**
	 * @method render_phone
	 */
	public function render_phone() {
	
		if ( $this->settings->phone ) {
		
			echo '<p class="fl-person-phone">';
			echo '<i class="icon fas fa-phone-square"></i> ';
			echo $this->settings->phone;
			echo '</p>';
			
		}
		
	}
	
	/**
	 * @method render_fax
	 */
	public function render_fax() {
	
		if ( $this->settings->fax ) {
	
			echo '<p class="fl-person-fax">';
			echo '<i class="icon fas fa-fax"></i> ';
			echo $this->settings->fax;
			echo '</p>';
			
		}
	}
	
	/**
	 * @method render_email
	 */
	public function render_email() {
	
		if ( $this->settings->email ) {

			echo '<p class="fl-person-email">';
			echo '<i class="icon fas fa-envelope-square"></i> ';
			
			echo $this->settings->email;
			echo '</p>';
			
		}
	}
	

	/**
	 * @method render_text
	 */
	public function render_text() {
		global $wp_embed;

		echo '<div class="fl-person-text">' . wpautop( $wp_embed->autoembed( $this->settings->text ) ) . '</div>';
	}


	/**
	 * Returns an array of settings used to render a photo module.
	 *
	 * @since 2.2
	 * @return array
	 */
	public function get_photo_settings() {
		$photo_data = FLBuilderPhoto::get_attachment_data( $this->settings->photo );

		if ( ! $photo_data && isset( $this->settings->photo_data ) ) {
			$photo_data = $this->settings->photo_data;
		} elseif ( ! $photo_data ) {
			$photo_data = -1;
		}

		$settings = array(
			'link_url_target' => $this->settings->link_target,
			'link_nofollow'   => $this->settings->link_nofollow,
			'link_type'       => 'url',
			'link_url'        => $this->settings->link,
			'photo'           => $photo_data,
			'photo_src'       => $this->settings->photo_src,
			'photo_source'    => 'library',
		);

		foreach ( $this->settings as $key => $value ) {
			if ( strstr( $key, 'photo_' ) ) {
				$key              = str_replace( 'photo_', '', $key );
				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}

	/**
	 * @method render_image
	 */
	public function render_image() {
		if ( empty( $this->settings->photo ) ) {
			return;
		}
		echo '<div class="fl-person-photo">';
		FLBuilder::render_module_html( 'photo', $this->get_photo_settings() );
		echo '</div>';
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('FLPersonModule', array(
	'general' => array(
		'title'    => __( 'General', 'fl-builder' ),
		'sections' => array(
			'information' => array(
				'title'  => '',
				'fields' => array(
					'name'     => array(
						'type'        => 'text',
						'label'       => __( 'Name', 'fl-builder' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.fl-person-name-text',
						),
						'connections' => array( 'string' ),
					),
					'title'     => array(
						'type'        => 'text',
						'label'       => __( 'Job Title', 'fl-builder' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.fl-person-title-text',
						),
						'connections' => array( 'string' ),
					),
					'email'     => array(
						'type'        => 'text',
						'label'       => __( 'Email address', 'fl-builder' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.fl-person-title-text',
						),
						'connections' => array( 'string' ),
					),
					'phone'     => array(
						'type'        => 'text',
						'label'       => __( 'Phone', 'fl-builder' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.fl-person-title-text',
						),
						'connections' => array( 'string' ),
					),
					'fax'     => array(
						'type'        => 'text',
						'label'       => __( 'Fax', 'fl-builder' ),
						'preview'     => array(
							'type'     => 'text',
							'selector' => '.fl-person-title-text',
						),
						'connections' => array( 'string' ),
					),

				),
			),
			'text'  => array(
				'title'  => __( 'Text', 'fl-builder' ),
				'fields' => array(
					'text' => array(
						'type'          => 'editor',
						'label'         => '',
						'media_buttons' => false,
						'wpautop'       => false,
						'preview'       => array(
							'type'     => 'text',
							'selector' => '.fl-person-text',
						),
						'connections'   => array( 'string' ),
					),
				),
			),
		),
	),
	'image'   => array(
		'title'    => __( 'Image', 'fl-builder' ),
		'sections' => array(
			'general'        => array(
				'title'  => '',
				'fields' => array(
					'image_type' => array(
						'type'    => 'select',
						'label'   => __( 'Image Type', 'fl-builder' ),
						'default' => 'photo',
						'options' => array(
							'photo' => __( 'Photo', 'fl-builder' ),
							'none'  => _x( 'None', 'Image type.', 'fl-builder' ),
						),
						'toggle'  => array(
							'none'  => array(),
							'photo' => array(
								'sections' => array( 'photo', 'photo_style' ),
							),

						),
					),
				),
			),
			'photo'          => array(
				'title'  => __( 'Photo', 'fl-builder' ),
				'fields' => array(
					'photo'          => array(
						'type'        => 'photo',
						'show_remove' => true,
						'label'       => __( 'Photo', 'fl-builder' ),
						'connections' => array( 'photo' ),
					),
					'photo_position' => array(
						'type'    => 'select',
						'label'   => __( 'Position', 'fl-builder' ),
						'default' => 'above-title',
						'options' => array(
							'above-title' => __( 'Above Heading', 'fl-builder' ),
							'below-title' => __( 'Below Heading', 'fl-builder' ),
							'left'        => __( 'Left of Text and Heading', 'fl-builder' ),
							'right'       => __( 'Right of Text and Heading', 'fl-builder' ),
						),
					),
				),
			),
			'photo_style'    => array(
				'title'  => __( 'Photo Style', 'fl-builder' ),
				'fields' => array(
					'photo_crop'   => array(
						'type'    => 'select',
						'label'   => __( 'Crop', 'fl-builder' ),
						'default' => '',
						'options' => array(
							''          => _x( 'None', 'Photo Crop.', 'fl-builder' ),
							'landscape' => __( 'Landscape', 'fl-builder' ),
							'panorama'  => __( 'Panorama', 'fl-builder' ),
							'portrait'  => __( 'Portrait', 'fl-builder' ),
							'square'    => __( 'Square', 'fl-builder' ),
							'circle'    => __( 'Circle', 'fl-builder' ),
						),
					),
					'photo_width'  => array(
						'type'       => 'unit',
						'label'      => __( 'Width', 'fl-builder' ),
						'responsive' => true,
						'units'      => array(
							'px',
							'vw',
							'%',
						),
						'slider'     => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-photo-img',
							'property'  => 'width',
							'important' => true,
						),
					),
					'photo_align'  => array(
						'type'       => 'align',
						'label'      => __( 'Align', 'fl-builder' ),
						'default'    => '',
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-photo',
							'property'  => 'text-align',
							'important' => true,
						),
					),
					'photo_border' => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'fl-builder' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-photo-img',
						),
					),
				),
			),
		),
	),
	'style'   => array(
		'title'    => __( 'Style', 'fl-builder' ),
		'sections' => array(
			'overall_structure' => array(
				'title'  => '',
				'fields' => array(
					'bg_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Background Color', 'fl-builder' ),
						'default'     => '',
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.fl-module-content',
							'property' => 'background-color',
						),
					),
					'border'   => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'fl-builder' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-module-content',
						),
					),
					'align'    => array(
						'type'       => 'align',
						'label'      => __( 'Alignment', 'fl-builder' ),
						'default'    => 'left',
						'help'       => __( 'The alignment that will apply to all elements within the person.', 'fl-builder' ),
						'responsive' => true,
						'preview'    => array(
							'type' => 'refresh',
						),
					),
					'padding'  => array(
						'type'       => 'dimension',
						'label'      => __( 'Padding', 'fl-builder' ),
						'default'    => '',
						'responsive' => true,
						'slider'     => true,
						'units'      => array(
							'px',
							'em',
							'%',
						),
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-module-content',
							'property' => 'padding',
						),
					),
				),
			),
			'photo_style'    => array(
				'title'  => __( 'Photo Style', 'fl-builder' ),
				'fields' => array(
					'photo_crop'   => array(
						'type'    => 'select',
						'label'   => __( 'Crop', 'fl-builder' ),
						'default' => '',
						'options' => array(
							''          => _x( 'None', 'Photo Crop.', 'fl-builder' ),
							'landscape' => __( 'Landscape', 'fl-builder' ),
							'panorama'  => __( 'Panorama', 'fl-builder' ),
							'portrait'  => __( 'Portrait', 'fl-builder' ),
							'square'    => __( 'Square', 'fl-builder' ),
							'circle'    => __( 'Circle', 'fl-builder' ),
						),
					),
					'photo_width'  => array(
						'type'       => 'unit',
						'label'      => __( 'Width', 'fl-builder' ),
						'responsive' => true,
						'units'      => array(
							'px',
							'vw',
							'%',
						),
						'slider'     => array(
							'px' => array(
								'min'  => 0,
								'max'  => 1000,
								'step' => 10,
							),
						),
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-photo-img',
							'property'  => 'width',
							'important' => true,
						),
					),
					'photo_align'  => array(
						'type'       => 'align',
						'label'      => __( 'Align', 'fl-builder' ),
						'default'    => '',
						'responsive' => true,
						'preview'    => array(
							'type'      => 'css',
							'selector'  => '.fl-photo',
							'property'  => 'text-align',
							'important' => true,
						),
					),
					'photo_border' => array(
						'type'       => 'border',
						'label'      => __( 'Border', 'fl-builder' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-photo-img',
						),
					),
				),
			),
			'title_structure'   => array(
				'title'  => __( 'Heading', 'fl-builder' ),
				'fields' => array(
					'title_color'      => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Heading Color', 'fl-builder' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.fl-person-title, .fl-person-title-text, .fl-person-title-text:hover',
							'property' => 'color',
						),
					),
					'title_typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Heading Typography', 'fl-builder' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-person-title',
						),
					),
				),
			),
			'content'           => array(
				'title'  => __( 'Text', 'fl-builder' ),
				'fields' => array(
					'content_color'      => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Text Color', 'fl-builder' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.fl-person-text, .fl-person-cta-link',
							'property' => 'color',
						),
					),
					'content_typography' => array(
						'type'       => 'typography',
						'label'      => __( 'Text Typography', 'fl-builder' ),
						'responsive' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.fl-person-text, .fl-person-cta-link',
						),
					),
				),
			),
		),
	),

));
