# Migrate tracking numbers from AfterShip to AST  

This plugin will migrate tracking information from the [AfterShip plguin for WooCommerce](https://wordpress.org/plugins/aftership-woocommerce-tracking/) into the [Advanced Shipment tracking plugin](https://wordpress.org/plugins/woo-advanced-shipment-tracking/) meta fields.

Once installed, navigate to WooCommerce > Migrate from Aftership to AST and you'll be able to choose the number of days to go back in orders and run the migration and click "Migrate".

## Installation

To install:

1. Download the latest version of the plugin [here](https://github.com/zorem/migrate-from-aftership-to-ast/archive/refs/heads/main.zip)
1. Go to **Plugins > Add New > Upload** administration screen on your WordPress site
1. Select the ZIP file you just downloaded
1. Click **Install Now**
1. Click **Activate**

### Map the Shipping provider names

You can use this code snippet in functions.php to map the shipping provider names in case the shipping provider name on AfterShip does not match the name of the provider on the AST shipping providers list.

```bash
function mfata_tracking_provider( $provider ) {  
  //Provider name on AfterShip 
  if ( 'usps-webhook' == $provider ) {  
     //Provider name on AST
     return 'usps';  
  }

  //Provider name on AfterShip
  if ( 'usps-api' == $provider ) { 		
    //Provider name on AST	
    return 'usps';      
   }	
   return $provider;
}
add_filter( 'mfata_tracking_provider', 'mfata_tracking_provider' );
```
