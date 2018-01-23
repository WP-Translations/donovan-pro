<?php
/**
 * Scroll to Top
 *
 * Displays scroll to top button based on theme options
 *
 * @package Donnager Pro
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Scroll to Top Class
 */
class Donnager_Pro_Scroll_To_Top {

	/**
	 * Scroll to Top Setup
	 *
	 * @return void
	 */
	static function setup() {

		// Return early if Donnager Theme is not active.
		if ( ! current_theme_supports( 'donnager-pro' ) ) {
			return;
		}

		// Enqueue Scroll-To-Top JavaScript.
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_script' ) );

		// Add Scroll-To-Top Checkbox in Customizer.
		add_action( 'customize_register', array( __CLASS__, 'scroll_to_top_settings' ) );
	}

	/**
	 * Enqueue Scroll-To-Top JavaScript
	 *
	 * @return void
	 */
	static function enqueue_script() {

		// Get Theme Options from Database.
		$theme_options = Donnager_Pro_Customizer::get_theme_options();

		// Call Credit Link function of theme if credit link is activated.
		if ( true === $theme_options['scroll_to_top'] ) :

			wp_enqueue_script( 'donnager-pro-scroll-to-top', DONNAGER_PRO_PLUGIN_URL . 'assets/js/scroll-to-top.js', array( 'jquery' ), DONNAGER_PRO_VERSION, true );

			// Passing Parameters to navigation.js.
			wp_localize_script( 'donnager-pro-scroll-to-top', 'donnager_pro_scroll_button', donnager_get_svg( 'collapse' ) );

		endif;
	}

	/**
	 * Add scroll to top checkbox setting
	 *
	 * @param object $wp_customize / Customizer Object.
	 */
	static function scroll_to_top_settings( $wp_customize ) {

		// Add Scroll to Top headline.
		$wp_customize->add_control( new Donnager_Customize_Header_Control(
			$wp_customize, 'donnager_theme_options[scroll_top_title]', array(
				'label'    => esc_html__( 'Scroll to Top', 'donnager-pro' ),
				'section'  => 'donnager_pro_section_footer',
				'settings' => array(),
				'priority' => 40,
			)
		) );

		// Add Scroll to Top setting.
		$wp_customize->add_setting( 'donnager_theme_options[scroll_to_top]', array(
			'default'           => false,
			'type'              => 'option',
			'transport'         => 'refresh',
			'sanitize_callback' => 'donnager_sanitize_checkbox',
		) );

		$wp_customize->add_control( 'donnager_theme_options[scroll_to_top]', array(
			'label'    => esc_html__( 'Display Scroll to Top Button', 'donnager-pro' ),
			'section'  => 'donnager_pro_section_footer',
			'settings' => 'donnager_theme_options[scroll_to_top]',
			'type'     => 'checkbox',
			'priority' => 50,
		) );
	}
}

// Run Class.
add_action( 'init', array( 'Donnager_Pro_Scroll_To_Top', 'setup' ) );