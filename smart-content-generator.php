<?php
/**
 * Plugin Name: Smart Content Generator
 * Plugin URI:  https://example.com/smart-content-generator
 * Description: Generate SEO-ready Gutenberg draft posts from pasted quotes, wishes, bios, captions, status, and shayari.
 * Version:     1.0.0
 * Author:      Abhijit Chavan
 * License:     GPL-2.0-or-later
 * Text Domain: smart-content-generator
 * Domain Path: /languages
 *
 * @package SmartContentGenerator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SCG_VERSION', '1.0.0' );
define( 'SCG_PLUGIN_FILE', __FILE__ );
define( 'SCG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SCG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SCG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

final class SCG_Plugin {
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		register_activation_hook( SCG_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( SCG_PLUGIN_FILE, array( $this, 'deactivate' ) );
		add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );
	}

	public function activate() {
		update_option( 'scg_version', SCG_VERSION, false );
		update_option( 'scg_installed_at', current_time( 'mysql' ), false );
	}

	public function deactivate() {}

	public function load_plugin() {
		$this->load_textdomain();
		$this->define_includes();
		if ( is_admin() ) {
			$admin = new SCG_Admin();
			$admin->init();
		}
	}

	private function load_textdomain() {
		load_plugin_textdomain( 'smart-content-generator', false, dirname( SCG_PLUGIN_BASENAME ) . '/languages' );
	}

	private function define_includes() {
		require_once SCG_PLUGIN_DIR . 'includes/class-admin.php';
	}
}

function scg_plugin() {
	return SCG_Plugin::instance();
}

scg_plugin();
