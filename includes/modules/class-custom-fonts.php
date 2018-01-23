<?php
/**
 * Custom Fonts
 *
 * Adds custom font settings to Customizer and generates font CSS code
 *
 * @package Donnager Pro
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Custom Fonts Class
 */
class Donnager_Pro_Custom_Fonts {

	/**
	 * Custom Fonts Setup
	 *
	 * @return void
	 */
	static function setup() {

		// Return early if Donnager Theme is not active.
		if ( ! current_theme_supports( 'donnager-pro' ) ) {
			return;
		}

		// Include Font List Control Files.
		require_once DONNAGER_PRO_PLUGIN_DIR . 'includes/customizer/class-font-list.php';
		require_once DONNAGER_PRO_PLUGIN_DIR . 'includes/customizer/class-customize-font-control.php';
		require_once DONNAGER_PRO_PLUGIN_DIR . 'includes/customizer/class-customize-font-list-control.php';

		// Add Custom Color CSS code to custom stylesheet output.
		add_filter( 'donnager_pro_custom_css_stylesheet', array( __CLASS__, 'custom_fonts_css' ) );

		// Load custom fonts from Google web font API.
		add_filter( 'donnager_google_fonts_url', array( __CLASS__, 'google_fonts_url' ) );

		// Add Font Settings in Customizer.
		add_action( 'customize_register', array( __CLASS__, 'font_settings' ) );
	}

	/**
	 * Adds Font Family CSS styles in the head area to override default typography
	 *
	 * @param String $custom_css Custom Styling CSS.
	 * @return string CSS code
	 */
	static function custom_fonts_css( $custom_css ) {

		// Get Theme Options from Database.
		$theme_options = Donnager_Pro_Customizer::get_theme_options();

		// Get Default Fonts from settings.
		$default_options = Donnager_Pro_Customizer::get_default_options();

		// Set Default Text Font.
		if ( $theme_options['text_font'] !== $default_options['text_font'] ) {

			$custom_css .= '
				/* Base Font Setting */
				body,
				button,
				input,
				select,
				textarea {
					font-family: "' . esc_attr( $theme_options['text_font'] ) . '";
				}
			';
		}

		// Set Title Font.
		if ( $theme_options['title_font'] !== $default_options['title_font'] ) {

			$custom_css .= '
				/* Title Font Setting */
				.site-title,
				.entry-title {
					font-family: "' . esc_attr( $theme_options['title_font'] ) . '";
				}
			';
		}

		// Set Navigation Font.
		if ( $theme_options['navi_font'] !== $default_options['navi_font'] ) {

			$custom_css .= '
				/* Navigation Font Setting */
				.main-navigation-toggle,
				.main-navigation-menu {
					font-family: "' . esc_attr( $theme_options['navi_font'] ) . '";
				}
				';

		}

		// Set Widget Title Font.
		if ( $theme_options['widget_title_font'] !== $default_options['widget_title_font'] ) {

			$custom_css .= '
				/* Widget Titles Font Setting */
				.widget-title,
				.archive-title,
				.comments-title,
				.comment-reply-title,
				.entry-author .author-heading .author-title {
					font-family: "' . esc_attr( $theme_options['widget_title_font'] ) . '";
				}
			';
		}

		return $custom_css;
	}

	/**
	 * Replace default Google Fonts URL with custom Fonts from theme settings
	 *
	 * @uses donnager_google_fonts_url filter hook
	 * @param String $google_fonts_url Google Fonts URL.
	 * @return string Google Font URL
	 */
	static function google_fonts_url( $google_fonts_url ) {

		// Get Theme Options from Database.
		$theme_options = Donnager_Pro_Customizer::get_theme_options();

		// Default Fonts which haven't to be load from Google.
		$default_fonts = Donnager_Pro_Custom_Font_Lists::default_browser_fonts();

		// Set Google Font Array.
		$google_font_families = array();

		// Set Font Styles.
		$font_styles = ':400,400italic,700,700italic';

		// Add Text Font.
		if ( isset( $theme_options['text_font'] ) and ! in_array( $theme_options['text_font'], $default_fonts ) ) {

			$google_font_families[] = $theme_options['text_font'] . $font_styles;
			$default_fonts[] = $theme_options['text_font'];

		}

		// Add Title Font.
		if ( isset( $theme_options['title_font'] ) and ! in_array( $theme_options['title_font'], $default_fonts ) ) {

			$google_font_families[] = $theme_options['title_font'] . $font_styles;
			$default_fonts[] = $theme_options['title_font'];

		}

		// Add Navigation Font.
		if ( isset( $theme_options['navi_font'] ) and ! in_array( $theme_options['navi_font'], $default_fonts ) ) {

			$google_font_families[] = $theme_options['navi_font'] . $font_styles;
			$default_fonts[] = $theme_options['navi_font'];

		}

		// Add Widget Title Font.
		if ( isset( $theme_options['widget_title_font'] ) and ! in_array( $theme_options['widget_title_font'], $default_fonts ) ) {

			$google_font_families[] = $theme_options['widget_title_font'] . $font_styles;
			$default_fonts[] = $theme_options['widget_title_font'];

		}

		// Return early if google font array is empty.
		if ( empty( $google_font_families ) ) {
			return $google_fonts_url;
		}

		// Setup Google Font URLs.
		$query_args = array(
			'family' => urlencode( implode( '|', $google_font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$google_fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );

		return $google_fonts_url;
	}

	/**
	 * Adds all font settings in the Customizer
	 *
	 * @param object $wp_customize / Customizer Object.
	 */
	static function font_settings( $wp_customize ) {

		// Add Section for Theme Fonts.
		$wp_customize->add_section( 'donnager_pro_section_fonts', array(
			'title'    => __( 'Typography', 'donnager-pro' ),
			'priority' => 70,
			'panel'    => 'donnager_options_panel',
		) );

		// Get Default Fonts from settings.
		$default_options = Donnager_Pro_Customizer::get_default_options();

		// Add Text Font setting.
		$wp_customize->add_setting( 'donnager_theme_options[text_font]', array(
			'default'           => $default_options['text_font'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Donnager_Pro_Customize_Font_Control(
			$wp_customize, 'text_font', array(
				'label'    => esc_html__( 'Base Font', 'donnager-pro' ),
				'section'  => 'donnager_pro_section_fonts',
				'settings' => 'donnager_theme_options[text_font]',
				'priority' => 10,
			)
		) );

		// Add Title Font setting.
		$wp_customize->add_setting( 'donnager_theme_options[title_font]', array(
			'default'           => $default_options['title_font'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Donnager_Pro_Customize_Font_Control(
			$wp_customize, 'title_font', array(
				'label'    => esc_html_x( 'Headings', 'Font Setting', 'donnager-pro' ),
				'section'  => 'donnager_pro_section_fonts',
				'settings' => 'donnager_theme_options[title_font]',
				'priority' => 20,
			)
		) );

		// Add Navigation Font setting.
		$wp_customize->add_setting( 'donnager_theme_options[navi_font]', array(
			'default'           => $default_options['navi_font'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Donnager_Pro_Customize_Font_Control(
			$wp_customize, 'navi_font', array(
				'label'    => esc_html_x( 'Navigation', 'Font Setting', 'donnager-pro' ),
				'section'  => 'donnager_pro_section_fonts',
				'settings' => 'donnager_theme_options[navi_font]',
				'priority' => 30,
			)
		) );

		// Add Widget Title Font setting.
		$wp_customize->add_setting( 'donnager_theme_options[widget_title_font]', array(
			'default'           => $default_options['widget_title_font'],
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_attr',
		) );

		$wp_customize->add_control( new Donnager_Pro_Customize_Font_Control(
			$wp_customize, 'widget_title_font', array(
				'label'    => esc_html_x( 'Widget Titles', 'Font Setting', 'donnager-pro' ),
				'section'  => 'donnager_pro_section_fonts',
				'settings' => 'donnager_theme_options[widget_title_font]',
				'priority' => 40,
			)
		) );

		// Available Fonts Setting.
		$wp_customize->add_setting( 'donnager_theme_options[available_fonts]', array(
			'default'           => 'favorites',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'Donnager_Pro_Custom_Fonts', 'sanitize_available_fonts' ),
		) );

		$wp_customize->add_control( new Donnager_Pro_Customize_Font_List_Control(
			$wp_customize, 'donnager_control_available_fonts', array(
				'label'    => esc_html__( 'Choose available Fonts', 'donnager-pro' ),
				'section'  => 'donnager_pro_section_fonts',
				'settings' => 'donnager_theme_options[available_fonts]',
				'choices'  => array(
					'default'   => esc_html__( 'Default Browser Fonts (12)', 'donnager-pro' ),
					'favorites' => esc_html__( 'ThemeZee Favorite Fonts (35)', 'donnager-pro' ),
					'popular'   => esc_html__( 'Most Popular Google Fonts (100)', 'donnager-pro' ),
					'all'       => esc_html__( 'All Google Fonts (650)', 'donnager-pro' ),
				),
				'priority' => 50,
			)
		) );
	}

	/**
	 *  Sanitize available fonts value.
	 *
	 * @param String $value / Value of the setting.
	 * @return string
	 */
	static function sanitize_available_fonts( $value ) {

		if ( ! in_array( $value, array( 'default', 'favorites', 'popular', 'all' ), true ) ) :
			$value = 'favorites';
		endif;

		return $value;
	}
}

// Run Class.
add_action( 'init', array( 'Donnager_Pro_Custom_Fonts', 'setup' ) );