<?php
namespace co2ok_plugin_woocommerce\Components;

use cbschuld\LogEntries;

class Co2ok_BewustBezorgd_API {
	private $token;
	private $client;

	function __construct($shop, $shopPostCode, $destPostCode, $shippingMethod, $weight) {
		//constructs shop and destination postcode and shipping method
		$this->shopPostCode = $shopPostCode;
		$this->destPostCode = $destPostCode;
		$this->shippingMethod = $shippingMethod;
		$this->weight = $weight;

		// array that holds id and password to access BB api
		$token = array(
			'id' => $shop->bbApiId,
			'password' => $shop->bbApiPass
		);
		$this->token = wp_json_encode( $token );

    //setup http requests
    $this->http = _wp_http_get_object();
		//base url for BB requests
    $this->baseUrl = 'https://emission.azurewebsites.net/';
	}

	public function storeOrderToBbApi($orderId) {

    //get tokens of store for BB
    $shopBbApiToken = get_option('bbApi_token', false);
		$shopBbApiTokenRefresh = get_option('bbApi_tokenRefresh', false);
		$shopBbApiTokenExpire = get_option('bbApi_tokenExpire', false);

		// if shop has token, refresh (if refresh needed) and store new token, else create new token for shop and store
		if ($shopBbApiToken) {
			if($this->checkExpireToken($shopBbApiTokenRefresh, $shopBbApiTokenExpire)) {
				if (!$this->refreshToken($shopBbApiTokenRefresh)) {
					return ;
				}
			}
		} else {
			try {
				$result = $this->http->post($baseUrl . "api/Account/Token", array(
					'headers'     => [
							'Accept' => 'application/json',
							'Content-Type' => 'application/json'
					],
					'body'        => $this->token,
					'data_format' => 'body',
					));

				//if no errors, tokens are retrieved and saved into shop
				$responseToken = json_decode($result['body'], true);
				if (!count($responseToken['errors'])) {
          add_option('bbApi_token', (string)$responseToken['accessToken']);
					add_option('bbApi_tokenRefresh', (string)$responseToken['refreshToken']);
					add_option('bbApi_tokenExpire', (string)$responseToken['expireDateTimeAccesToken']);
					$shopBbApiToken = get_option('bbApi_token', false);
				} else {
					Co2ok_BewustBezorgd_API::remoteLogging(json_encode(["Logging BB Api error response: ", $response['errors']]));
					return ;
				}
			} catch (RequestException $e) {
				$this->catchException($e);
				return ;
			}
		}

		//check shipping method selected is accepted by BB api
		$shippingChoice = $this->correctShipping($this->shippingMethod);

		// Q&D PC cleanup
		if (!preg_match("/[a-zA-Z]+$/", substr($this->shopPostCode, -2)))
			$this->shopPostCode .= 'JS';

		if (!preg_match("/[a-zA-Z]+$/", substr($this->destPostCode, -2)))
			$this->destPostCode .= 'JS';

		//create query including all necessary info
		$query = '?FromPostalCode='.$this->shopPostCode;
		$query .= '&FromCountry=NL';
		$query .= '&ToPostalCode='.$this->destPostCode;
		$query .= '&ToCountry=NL';
		$query .= '&Weight='.$this->weight;
		$query .= '&ServiceType='.$shippingChoice;

		//calculate emissions, diesel and gas
		try {
      $result = $this->http->get($this->baseUrl . 'api/emission-calculation/two-legs' . $query, array(
        'headers'     => [
					'Authorization' => 'Bearer ' . $shopBbApiToken
				],
        'body'        => $this->token,
        'data_format' => 'body',
      ));

			$responseTwoLegs = json_decode($response['body'], true);
			if (count($responseTwoLegs['errors'])) {
				Co2ok_BewustBezorgd_API::remoteLogging(json_encode(["Logging BB Api error response: ", $responseTwoLegs['errors']]));
				return ;
      // } else {
				//log info somewhere?
				//$emissionsGrams = $responseTwoLegs['emission'];
				//$diesel = $responseTwoLegs['metersDiesel'];
				//$gasoline = $responseTwoLegs['metersGasoline'];
			}
		} catch (RequestException $e) {
			$this->catchException($e);
			return ;
		}

		//store the shipment details in the BB api
		try {
      $result = $this->http->get($this->baseUrl . 'api/emission-calculation/two-legs-checkout' . $query, array(
        'headers'     => [
					'Authorization' => 'Bearer ' . $shopBbApiToken
				],
        'body'        => $this->token,
        'data_format' => 'body',
      ));
			if ( ! wp_remote_retrieve_response_code($result) == 204 ) {
				Co2ok_BewustBezorgd_API::remoteLogging(json_encode(["Logging emissions predictions error stored"]));
				return ;
			}
		} catch (RequestException $e) {
			$this->catchException($e);
			return ;
		}
	}


	//checks if token is expired by date
	public function checkExpireToken($refreshToken, $expireDate) {
		date_default_timezone_set('Europe/Berlin');
		$currentDate = new \DateTime(date('d-m-Y h:i:s a', time()));
		$expire = new \DateTime($expireDate);
		if ($currentDate > $expire) {
			return true;
		}
		return false;
	}


	//refreshes and returns new token to access BB API
	public function refreshToken($refreshAccessToken) {
		try {
      $refreshArray = array(
				'refreshToken' => $refreshAccessToken
      );

      $result = $this->http->post($this->baseUrl . 'api/Account/Refresh', array(
        'headers'     => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ],
        'body'        => json_encode($refreshArray),
        'data_format' => 'body',
      ));
			$response = json_decode($result['body'], true);
			if (count($response['errors'])) {
				return false;
			}
		} catch (RequestException $e) {
			$this->catchException($e);
			return false;
		}

    update_option('bbApi_token', $response['accessToken']);
    update_option('bbApi_tokenRefresh', $response['refreshToken']);
    update_option('bbApi_tokenExpire', $response['expireDateTimeAccesToken']);

		return true;
	}

	//corrects shipping method if its not accepted by BB API
	public function correctShipping($shipping) {
		$shippingCategory = array (
			'NextDay',
			'SmallTimeframe',
			'MediumTimeframe',
			'EveningDelivery',
			'SameDay',
			'SundayDelivery'
		);
		foreach ($shippingCategory as $method) {
			if ($method == $shipping)
				return $shipping;
		}
		return 'NextDay';
	}

	//catch exception for Guzzle client
	public function catchException($e) {
		$error['error'] = $e->getMessage();
		$error['request'] = $e->getRequest();
		if($e->hasResponse()){
		  $error['response'] = $e->getResponse();
		}
		Co2ok_BewustBezorgd_API::remoteLogging(json_encode(["Error occurred in BB API request ",  $error]));
	}

	final public static function remoteLogging($message = "Unspecified message.")
    {

        // Write to remote log
        try {
            // Only called when user has opted in to allow anymous tracking
            // @reviewers: we've done our best to limit the amount of logging, please
            // contact us if this approach is unacceptable
            //
            $token = "8acac111-633f-46b3-b14b-1605e45ae614"; // our LogEntries token
            $remote = LogEntries::getLogger($token, true, true);
            $remote->info( $message );
        } catch (Exception $e) { // fail silently
        }
    }
}
