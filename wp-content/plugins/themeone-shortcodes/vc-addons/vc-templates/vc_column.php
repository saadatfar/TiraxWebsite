<?php
/**
 * @var $this WPBakeryShortCode_VC_Column
 */
$output = $font_color = $el_class = $width = $offset = '';
$output = '';
extract( shortcode_atts( array(
	'font_color' => '',
	'el_class' => '',
	'width' => '1/1',
	'css' => '',
	'offset' => '',
	'txt_column_align' => '',
	'anim' => '',
	'delay' => ''
), $atts ) );
$animClass = '';
if ($anim != '') {
	$delay = preg_replace('/[^\d-]+/', '', $delay);
	$anim = 'data-anim="'. esc_attr($anim) .'" data-delay="'. esc_attr($delay) .'"';
	$animClass = ' has-anim ';
}

$el_class = $this->getExtraClass( $el_class );
$width = wpb_translateColumnWidthToSpan( $width );
$width = vc_column_offset_class_merge( $offset, $width );
$el_class .= ' wpb_column vc_column_container';

$css_classes = array(
	$this->getExtraClass( $el_class ),
	'wpb_column',
	'vc_column_container',
	$width,
	$animClass,
	$txt_column_align
);

if (vc_shortcode_custom_css_has_property( $css, array('border', 'background') )) {
	$css_classes[]='vc_col-has-fill';
}

$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $css_classes ) ), $this->settings['base'], $atts ) );
$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) .'"';

if (preg_match("/\{(.*?)\}/s", $css, $match)) {
	$inline_style = 'style="'.$match[1].'"';
} else {
	$inline_style = '';
}

$output .= '<div ' . implode( ' ', $wrapper_attributes ) . ' '.$anim .' ' . $inline_style . '>';
	$output .= '<div class="vc_column-inner" >';
		$output .= '<div class="wpb_wrapper">';
		$output .= wpb_js_remove_wpautop( $content );
		$output .= '</div>';
	$output .= '</div>';
$output .= '</div>';

echo $output;