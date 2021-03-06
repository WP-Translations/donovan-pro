<?php
/**
 * Customizer
 *
 * Setup the Customizer and theme options for the Pro plugin
 *
 * @package Donovan Pro
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Customizer Class
 */
class Donovan_Pro_Customizer {

	/**
	 * Customizer Setup
	 *
	 * @return void
	 */
	static function setup() {

		// Return early if Donovan Theme is not active.
		if ( ! current_theme_supports( 'donovan-pro' ) ) {
			return;
		}

		// Enqueue scripts and styles in the Customizer.
		add_action( 'customize_preview_init', array( __CLASS__, 'customize_preview_js' ) );
		add_action( 'customize_controls_print_styles', array( __CLASS__, 'customize_preview_css' ) );

		// Remove Upgrade section.
		remove_action( 'customize_register', 'donovan_customize_register_upgrade_settings' );
	}

	/**
	 * Get saved user settings from database or plugin defaults
	 *
	 * @return array
	 */
	static function get_theme_options() {

		// Merge Theme Options Array from Database with Default Options Array.
		$theme_options = wp_parse_args( get_option( 'donovan_theme_options', array() ), self::get_default_options() );

		// Return theme options.
		return $theme_options;

	}


	/**
	 * Returns the default settings of the plugin
	 *
	 * @return array
	 */
	static function get_default_options() {

		$default_options = array(
			'header_text'        => '',
			'header_date'        => false,
			'header_search'      => false,
			'author_bio'         => false,
			'footer_content'     => false,
			'footer_text'        => '',
			'credit_link'        => true,
			'scroll_to_top'      => false,
			'link_color'         => '#ee1133',
			'button_color'       => '#ee1133',
			'button_hover_color' => '#D5001A',
			'navi_color'         => '#202020',
			'navi_submenu_color' => '#ee1133',
			'title_color'        => '#202020',
			'widget_title_color' => '#202020',
			'footer_color'       => '#202020',
			'text_font'          => 'Raleway',
			'title_font'         => 'Quicksand',
			'navi_font'          => 'Quicksand',
			'widget_title_font'  => 'Quicksand',
		);

		return $default_options;

	}

	/**
	 * Embed JS file to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @return void
	 */
	static function customize_preview_js() {

		wp_enqueue_script( 'donovan-pro-customizer-js', DONOVAN_PRO_PLUGIN_URL . 'assets/js/customizer.js', array( 'customize-preview' ), DONOVAN_PRO_VERSION, true );

	}

	/**
	 * Embed CSS styles for the theme options in the Customizer
	 *
	 * @return void
	 */
	static function customize_preview_css() {

		wp_enqueue_style( 'donovan-pro-customizer-css', DONOVAN_PRO_PLUGIN_URL . 'assets/css/customizer.css', array(), DONOVAN_PRO_VERSION );

	}
}

// Run Class.
add_action( 'init', array( 'Donovan_Pro_Customizer', 'setup' ) );
