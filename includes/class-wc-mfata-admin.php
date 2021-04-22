<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Mfata_Admin {
	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();
	}

	/**
	 * Get the class instance
	 *
	 * @return Ptw_Admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	 * init function
	*/
	public function init() {	
		
		//load css/javascript in admin
		add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_style' ) );	
		
		//Custom Woocomerce menu
		add_action('admin_menu', array( $this, 'register_woocommerce_menu_mfata' ), 99 );	
		
		//ajax save admin settings
		add_action( 'wp_ajax_mfata_migrate_data', array( $this, 'mfata_migrate_data_callback' ) );
	}
	
	/*
	* Add admin javascript
	*/	
	public function admin_enqueue_style() {		
		
		$page = isset( $_GET['page'] ) ? wc_clean( $_GET['page'] ) : '';
		
		if ( 'migrate-from-aftership-to-ast' == $page ) {	
			
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';	

			wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
			wp_enqueue_style( 'woocommerce_admin_styles' );
		
			wp_enqueue_script( 'mfata-js', migrate_aftership_to_ast()->plugin_dir_url() . 'assets/js/admin.js', array('jquery'), migrate_aftership_to_ast()->version );
		
			wp_enqueue_style( 'mfata-css', migrate_aftership_to_ast()->plugin_dir_url() . 'assets/css/admin.css', array(), migrate_aftership_to_ast()->version );				
		}
		
	}
	
	/*
	* Admin Menu add function
	* WC sub menu
	*/
	public function register_woocommerce_menu_mfata() {
		add_submenu_page( 'woocommerce', 'Migrate from Aftership to AST', 'Migrate from Aftership to AST', 'manage_woocommerce', 'migrate-from-aftership-to-ast', array( $this, 'mfata_callback' ) ); 
	}
	
	/*
	* callback for Customer Email Verification page
	*/
	public function mfata_callback() { 
		?>
		<div class="zorem-layout">
			<div class="zorem-layout-mfata__header">
				<h1 class="zorem-layout-mfata__header-breadcrumbs">Migrate from Aftership to AST</h1>				
			</div>
			<div class="woocommerce mfata_admin_layout">
				<div class="mfata_admin_content" >
					<?php require_once( 'views/settings_tab.php' ); ?>											
				</div> 
			</div>
		</div>
	<?php 
	}
	
	/*
	* migration data callback	
	*
	* @since   1.0
	*/
	public function mfata_migrate_data_callback() {		
		check_ajax_referer( 'mfata_settings_tab', 'mfata_settings_tab_nonce' );
		
		if ( !function_exists( 'ast_insert_tracking_number' ) ) {
			return;
		}
			
		$days = wc_clean( $_POST['migration_data_duration'] );		

		$orders = new WP_Query(
			array(
				'post_type'      => 'shop_order',
				'post_status'    => 'any',
				'posts_per_page' => -1,		
				'fields' => 'ids',	
				'meta_query' => array(
					'relation' => 'AND', 
					array(
						'key'       => '_aftership_tracking_items',
						'compare'     => 'EXISTS'
					),
					array(
						'key'       => '_migrate_from_aftership_to_ast',
						'compare'     => 'NOT EXISTS'
					),					
				),	
				'date_query' => array(
					array(
						'after' => '-'.$days.' days'
					)
				)
			)
		);		
			
		//echo '<pre>';print_r($order_id);echo '</pre>';exit;
		foreach ( $orders->posts as $order_id ) {			
			$tracking_items = $this->get_aftership_tracking_items( $order_id, true );	
			foreach ( $tracking_items as $tracking_item ) {
				
				$tracking_provider = apply_filters( 'mfata_tracking_provider', $tracking_item['slug'] );
				ast_insert_tracking_number( $order_id, $tracking_item['tracking_number'], $tracking_provider );
			}
			update_post_meta( $order_id, '_migrate_from_aftership_to_ast', true );
		}			
		die();	
	} 
	
	/*
	 * Gets all tracking items from the post meta array for an order
	 *
	 * @param int  $order_id  Order ID
	 *
	 * @return array List of tracking items
	 */
	public function get_aftership_tracking_items( $order_id ) {

		if ( version_compare( WC_VERSION, '3.0', '<' ) ) {
			$tracking_items = get_post_meta( $order_id, '_aftership_tracking_items', true );
		} else {
			$order          = new WC_Order( $order_id );
			$tracking_items = $order->get_meta( '_aftership_tracking_items', true );
		}

		if ( is_array( $tracking_items ) ) {
			return $tracking_items;
		} else {
			return array();
		}
	}
}

// The filter callback function.
function mfata_tracking_provider( $provider ) {
    if ( 'usps-webhook' == $provider ) {
		return 'usps';
	}
	if ( 'usps-api' == $provider ) {
		return 'usps';
	}
    return $provider;
}
add_filter( 'mfata_tracking_provider', 'mfata_tracking_provider' );