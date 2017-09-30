<?php
/**
 * Class Autoloader
 *
 *
 */

spl_autoload_register( 'co2ok_plugin_woocommerce_autoload' );

function co2ok_plugin_woocommerce_autoload( $class_name ) {

    if ( false === strpos( $class_name, 'co2ok_plugin_woocommerce' ) ) {
        return;
    }

    $file_name = str_ireplace( '_', '-', $class_name );
    $file_name = str_ireplace( '\\', '/', $file_name );

    $filepath  = trailingslashit( dirname( dirname( __FILE__ ) )  );
    $filepath .= $file_name.'.php';

    if ( file_exists( $filepath ) )
    {
        if ( !class_exists( $class_name ) )
            include_once( $filepath );
    }
}