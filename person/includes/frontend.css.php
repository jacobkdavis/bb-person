<?php

// Alignment
$align            = isset( $settings->align ) && 'right' == $settings->align ? 'right' : 'none';
$align_medium     = isset( $settings->align_medium ) && 'right' == $settings->align_medium ? 'right' : 'none';
$align_responsive = isset( $settings->align_responsive ) && 'right' == $settings->align_responsive ? 'right' : 'none';

FLBuilderCSS::responsive_rule( array(
	'settings'     => $settings,
	'setting_name' => 'align',
	'selector'     => ".fl-node-$id .fl-person",
	'prop'         => 'text-align',
) );

// Photo Styles
if ( 'photo' == $settings->image_type && ! empty( $settings->photo ) ) {
	FLBuilder::render_module_css( 'photo', $id, $module->get_photo_settings() );
}

// Background Color
FLBuilderCSS::rule( array(
	'selector' => ".fl-node-$id .fl-module-content",
	'props'    => array(
		'background-color' => $settings->bg_color,
	),
) );

// Border
FLBuilderCSS::border_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'border',
	'selector'     => ".fl-node-$id .fl-module-content",
) );

// Wrapper Padding
FLBuilderCSS::dimension_field_rule( array(
	'settings'     => $settings,
	'setting_name' => 'padding',
	'selector'     => ".fl-node-$id .fl-module-content",
	'props'        => array(
		'padding-top'    => 'padding_top',
		'padding-right'  => 'padding_right',
		'padding-bottom' => 'padding_bottom',
		'padding-left'   => 'padding_left',
	),
) );

// Title Color
FLBuilderCSS::rule( array(
	'selector' => ".fl-builder-content .fl-node-$id .fl-person-content .fl-person-title, .fl-builder-content .fl-node-$id .fl-person-content .fl-person-title-text, .fl-builder-content .fl-node-$id .fl-person-content .fl-person-title-text:hover",
	'props'    => array(
		'color' => $settings->title_color,
	),
) );

// Title Typography
FLBuilderCSS::typography_field_rule( array(
	'selector'     => ".fl-node-$id .fl-person-title",
	'setting_name' => 'title_typography',
	'settings'     => $settings,
) );

// Content Color
FLBuilderCSS::rule( array(
	'selector' => ".fl-builder-content .fl-node-$id .fl-person-content .fl-person-text *, .fl-builder-content .fl-node-$id .fl-person-content .fl-person-cta-link",
	'props'    => array(
		'color' => $settings->content_color,
	),
) );

// Content Typography
FLBuilderCSS::typography_field_rule( array(
	'selector'     => ".fl-node-$id .fl-person-text, .fl-node-$id .fl-person-cta-link",
	'setting_name' => 'content_typography',
	'settings'     => $settings,
) );

