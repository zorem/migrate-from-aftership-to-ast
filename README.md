# Migrate tracking numbers from AfterShip to AST  

This plugin will migrate tracking information from the AfterShip WooCommerce plguin to the Advanced Shipment tracking plguin meta fields.

## Installation

To install:

1. Download the latest version of the plugin [here](https://github.com/zorem/migrate-from-aftership-to-ast/archive/refs/heads/main.zip)
1. Go to **Plugins > Add New > Upload** administration screen on your WordPress site
1. Select the ZIP file you just downloaded
1. Click **Install Now**
1. Click **Activate**

### Map the Shipping provider names

You can use this code snippet in functions.php to maop the shipping provider names in case the shipping provider name on AfterShip does not match the name of the provider on the AST shipping providers list.

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
