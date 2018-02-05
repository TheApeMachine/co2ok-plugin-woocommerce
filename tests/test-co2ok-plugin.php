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
		$this->assertEquals( Co2ok_Plugin::$co2okApiUrl, "https://test-api.co2ok.eco/graphql" );
		$this->assertTrue( filter_var(get_option('admin_email'), FILTER_VALIDATE_EMAIL) );
		$this->assertTrue( isset($_SERVER['SERVER_NAME']) );
		$this->assertFalse( get_option('co2ok_id') );
		$this->assertFalse( get_option('co2ok_secret') );

		// Execute method
		try {
			$this->plugin->registerMerchant();
		} catch (Exception $e) {
			$this->assertWPError($e);
		}

		// Test postconditions
		$this->assertEquals( $this->isUuidv4(get_option('co2ok_id')) );
		$this->assertEquals( $this->isUuidv4(get_option('co2ok_secret')) );

		// Reset postcontions
		delete_option('co2ok_id');
		delete_option('co2ok_secret');
	}
	
	/**
	 * Tests whether string is a valid UUIDv4
	 * Returns a boolean
	 */
	private function isUuidv4($str) {
		$UUIDv4 = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
		return preg_match($UUIDv4, $value) === 1;
	}
}
?>
