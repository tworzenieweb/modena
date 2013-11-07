<?php
/*
Plugin Name: Recent Posts Widget Extended
Plugin URI: http://wordpress.org/plugins/recent-posts-widget-extended/
Description: Enables recent posts widget with advanced settings.
Version: 0.8.1
Author: Satrya
Author URI: http://tokokoo.com
Author Email: satrya@tokokoo.com
License: GPLv2
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'RPW_Extended' ) ) :

class RPW_Extended {

	/**
	 * PHP5 constructor method.
	 *
	 * @since 0.1
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( &$this, 'constants' ), 1 );

		add_action( 'plugins_loaded', array( &$this, 'i18n' ), 2 );

		add_action( 'plugins_loaded', array( &$this, 'includes' ), 3 );

		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_style' ) );

	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 0.1
	 */
	public function constants() {

		define( 'RPWE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		define( 'RPWE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		define( 'RPWE_INCLUDES', RPWE_DIR . trailingslashit( 'includes' ) );

	}

	/**
	 * Loads the translation files.
	 *
	 * @since 0.1
	 */
	public function i18n() {
		/* Load the translation of the plugin. */
		load_plugin_textdomain( 'rpwe', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since 0.1
	 */
	public function includes() {
		require_once( RPWE_INCLUDES . 'widget-recent-posts-extended.php' );
	}

	/**
	 * Register custom style for the widget settings.
	 *
	 * @since 0.8
	 */
	function admin_style() {
		wp_enqueue_style( 'rpwe-admin-style', RPWE_URI . 'includes/admin.css' );
	}

}

new RPW_Extended;
endif;
?>