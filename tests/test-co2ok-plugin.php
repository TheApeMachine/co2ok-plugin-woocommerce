<?php
/**
 * Class Co2okPluginTest
 *
 * @package Co2ok_Plugin
 */

class Co2okPluginTest extends WP_UnitTestCase {

	private $plugin;

	/**
	 * Constructor
	 * Initializes a Co2ok_Plugin instance
	 */
	final public function __construct() {
		$this->plugin = new \co2ok_plugin_woocommerce\Co2ok_Plugin();
	}

	/**
	 * Unit test for registerMerchant method
	 * TBD: Namespace access for Co2ok_Plugin
	 */
	final public function test_registerMerchant() {
		// Test preconditions
		$this->assertTrue( filter_var(Co2ok_Plugin::$co2okApiUrl, FILTER_VALIDATE_URL) );
		$this->assertTrue( filter_var(get_option('admin_email'), FILTER_VALIDATE_EMAIL) );
		$this->assertTrue( isset($_SERVER['SERVER_NAME']) );
		$this->assertFalse( get_option('co2ok_id') );
		$this->assertFalse( get_option('co2ok_secret') );

		// Setup sandbox
		Co2ok_Plugin::$co2okApiUrl = 'localhost';
		// TODO: local sandbox for API server

		// Test random values multiple times
		foreach (range(0, 50) as $iter) {
			$id     = uniqid(); // TODO: specify possible configurations
			$secret = uniqid(); // TODO: specify possible configurations
			// TODO: set values to local sandbox

			// Execute method
			try {
				$this->plugin->registerMerchant();
			} catch (Exception $e) {
				$this->assertWPError($e);
			}

			// Test postconditions
			$this->assertEquals( $id, get_option('co2ok_id') );
			$this->assertEquals( $id, get_option('co2ok_secret') );

			// Reset postcontions
			delete_option('co2ok_id');
			delete_option('co2ok_secret');
		}
	}
}
?>
