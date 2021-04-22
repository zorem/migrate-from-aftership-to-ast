<?php
/**
 * @wordpress-plugin
 * Plugin Name: Migrate from AfterShip to Advanced Shipment Tracking for WooCommerce
 * Plugin URI: # 
 * Description: Migrate from AfterShip to Advanced Shipment Tracking for WooCommerce
 * Version: 1.0.0
 * Author: zorem
 * Author URI: https://www.zorem.com 
 * License: GPL-2.0+
 * License URI: 
 * Text Domain: migrate-from-aftership-to-ast
 * WC tested up to: 5.2.2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Migrate_Aftership_To_Ast {
	
	/**
	 * Initialize version number for use in javascript and css for caching
	*/
	public $version = '1.0.0';
	
	/**
	 * Paypal Tracking For Woocommerce
	 *
	 * @ auto loded when object created
	*/	
	public function __construct() {
	
		if ( $this->is_wc_active() && $this->is_ast_active() ) {
			$this->includes();
			$this->init();
		}
		
	}
	
	/*
	* include files
	*/
	private function includes() {				
		
		require_once $this->get_plugin_path() . '/includes/class-wc-mfata-admin.php';
		$this->admin = WC_Mfata_Admin::get_instance();
	}
	
	/*
	 * init function
	*/
	public function init() {	
	
	}
	
	/**
	* Gets the absolute plugin path without a trailing slash, e.g.
	* /path/to/wp-content/plugins/plugin-directory.
	*
	* @return string plugin path
	*/
	public function get_plugin_path() {
		
		if ( isset( $this->plugin_path ) ) {
			return $this->plugin_path;
		}

		$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

		return $this->plugin_path;
	}

	public function plugin_dir_url() {
		return plugin_dir_url( __FILE__ );
	}
	
	/**
	 * Check if WooCommerce is active	 	
	 * @since  1.0.0
	 * @return bool
	*/
	private function is_wc_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}
		

		// Do the WC active check
		if ( false === $is_active ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
		}		
		return $is_active;
	}
	
	/**
	 * Display WC active notice	 
	 * @since  1.0.0
	*/
	public function notice_activate_wc() {
		?>
		<div class="error">
			<p><?php 
			/* translators: %s: search WooCommerce plugin link */
			printf( esc_html__( 'Please install and activate %1$sWooCommerce%2$s for Migrate from AfterShip Order Tracking to AST!', 'migrate-from-aftership-to-ast' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) ) . '">', '</a>' ); ?></p>
		</div>
		<?php
	}
	
	/**
	* Check if Advanced Shipment Tracking for WooCommerce is active
	*
	* @since  1.0.0
	*
	* @return bool
	*/
	private function is_ast_active() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
		
		if ( is_plugin_active( 'woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php' ) || is_plugin_active( 'woocommerce-shipment-tracking/woocommerce-shipment-tracking.php' ) ) {
			$is_active = true;
		} else {
			$is_active = false;
		}

		// Do the WC active check
		if ( false === $is_active ) {
			add_action( 'admin_notices', array( $this, 'notice_activate_ast' ) );
		}
		return $is_active;
	}
	
	/**
	 * Display AST active notice
	 *
	 * @since  1.0.0
	*/
	public function notice_activate_ast() {
		?>
		<div class="error">		
			<p>
			<?php 
			/* translators: %s: search WooCommerce Shipment Tracking or Advanced Shipment Tracking for WooCommerce plugin link */
			printf( esc_html__( 'You must install and activate the %1$sAdvanced Shipment Tracking for WooCommerce%2$s plugin for the Migrate from AfterShip Order Tracking to AST to work', 'migrate-from-aftership-to-ast' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?s=ast+zorem&tab=search&type=term' ) ) . '">', '</a>' );
			?>
			</p>
		</div>
		<?php
	}
}	


function migrate_aftership_to_ast() {
	static $instance;

	if ( ! isset( $instance ) ) {
		$instance = new Migrate_Aftership_To_Ast();
	}

	return $instance;
}
migrate_aftership_to_ast();