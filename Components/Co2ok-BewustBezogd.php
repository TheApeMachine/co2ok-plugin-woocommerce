<?php
namespace co2ok_plugin_woocommerce\Components;

// use GuzzleHttp\Client;
// use Illuminate\Support\Facades\Log;
// use \GuzzleHttp\Exception\RequestException;

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
		$this->token = array(
			'id' => $shop->bbApiId,
			'password' => $shop->bbApiPass
		);

    //setup http requests
    $this->http = _wp_http_get_object();
		//base url for BB requests
    $this->baseUrl = 'https://emission.azurewebsites.net/';


		// $this->client = new Client([
		// 	'base_uri' => 'https://emission.azurewebsites.net/',
		// 	'headers' => [
		// 		'Accept' => 'application/json',
		// 		'Content-Type' => 'application/json'
		// 	]
		// ]);
	}

	public function storeOrderToBbApi($orderId) {

    //get tokens of store for BB
    $shopBbApiToken = get_option('bbApi_token', false);
    $shopBbApiTokenRefresh = get_option('bbApi_tokenRefresh', false);
    $shopBbApiTokenExpire = get_option('bbApi_tokenExprie', false);

		// if shop has token, refresh (if refresh needed) and store new token, else create new token for shop and store
		if ($shopBbApiToken) {
			if($this->checkExpireToken($shopBbApiTokenRefresh, $shopBbApiTokenExpire)) {
				if (!$this->refreshToken($shopBbApiTokenRefresh)) {
					return ;
				}
			}
		} else {
			try {
          $result = $this->http->post($this->baseUrl . "api/Account/Token", array(
            'headers'     => [
              'Accept' => 'application/json',
		      		'Content-Type' => 'application/json'
            ],
            'body'        => json_encode($this->token),
            'method'      => 'POST',
            'data_format' => 'body',
        ));


			  //  $result = $this->client->post( 'api/Account/Token', [
				// 	'json' => [
				// 		'id' => $this->token['id'],
				// 		'password' => $this->token['password']
				// 	],
				// ]);
				//if no errors, tokens are retrieved and saved into shop

        // $response_code = wp_remote_retrieve_response_code($response);
				$responseToken = json_decode($result->getBody(), true);
				if (!count($responseToken['errors'])) {
          add_option('bbApi_token', (string)$response['accessToken']);
          add_option('bbApi_tokenRefresh', (string)$response['refreshToken']);
          add_option('bbApi_tokenExpire', (string)$response['expireDateTimeAccesToken']);
          $shopBbApiToken = get_option('bbApi_token', false);
				} else {
          
					// Log::error("Logging BB Api error response: " . print_r($response['errors'], true));
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
      $result = $this->http->get($this->baseUrl . 'api/emission-calculation/two-legs'.$query, array(
        'headers'     => [
					'Authorization' => 'Bearer ' . $shopBbApiToken
				],
        'body'        => json_encode($this->token),
        'method'      => 'GET',
        'data_format' => 'body',
      ));

			// $result = $this->client->get( 'api/emission-calculation/two-legs'.$query, [
			// 	'headers' => [
			// 		'Authorization' => 'Bearer ' . $shop->bbApiToken
			// 	]
			// ]);
			$responseTwoLegs = json_decode($result->getBody(), true);
			if (count($responseTwoLegs['errors'])) {
				Log::error("Logging BB Api error response: " . print_r($responseTwoLegs, true));
				return ;
      }
			// } else {
			// 	//the order is saved to the local and to DynamoDB
			// 	$order = new \App\bb_order_emission;
			// 	$order->orderId = $orderId;
			// 	$order->merchantId = $shop->merchantId;
			// 	$order->distributionPostcode = $this->shopPostCode;
			// 	$order->destinationPostcode = $this->destPostCode;
			// 	$order->weight = $this->weight;
			// 	$order->shippingMethod = $shippingChoice;
			// 	$order->emissionsGrams = $responseTwoLegs['emission'];
			// 	$order->diesel = $responseTwoLegs['metersDiesel'];
			// 	$order->gasoline = $responseTwoLegs['metersGasoline'];
			// 	//using laravel-dynamodb -> saves new order to DynamoDB table Order
			// 	$order->save();
			// }
		} catch (RequestException $e) {
			$this->catchException($e);
			return ;
		}
		//store the shipment details in the BB api
		try {
      $result = $this->http->get($this->baseUrl . 'api/emission-calculation/two-legs-checkout'.$query, array(
        'headers'     => [
					'Authorization' => 'Bearer ' . $shopBbApiToken
				],
        'body'        => json_encode($this->token),
        'method'      => 'GET',
        'data_format' => 'body',
      ));

			// $this->client->get( 'api/emission-calculation/two-legs-checkout'.$query, [
			// 	'headers' => [
			// 		'Authorization' => 'Bearer ' . $shop->bbApiToken
			// 	]
			// ]);
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
		Log::info("Refreshing Token");
		try {

      $refreshJson = array(
        'json' => [
					'refreshToken' => $refreshAccessToken
				]
      );

      $result = $this->http->post($this->baseUrl . 'api/Account/Refresh', array(
        'headers'     => [
          'Accept' => 'application/json',
          'Content-Type' => 'application/json'
        ],
        'body'        => json_encode($refreshJson),
        'method'      => 'POST',
        'data_format' => 'body',
      ));


			// $result = $this->client->post( 'api/Account/Refresh', [
			// 	'json' => [
			// 		'refreshToken' => $refreshAccessToken
			// 	],
			// ]);
			$response = json_decode($result->getBody(), true);
			if (count($response['errors'])) {
				Log::error("Logging BB Api error response: " . print_r($response, true));
			}
		} catch (RequestException $e) {
			$this->catchException($e);
			return false;
		}

    update_option('bbApi_token', (string)$response['accessToken']);
    update_option('bbApi_tokenRefresh', (string)$response['refreshToken']);
    update_option('bbApi_tokenExpire', (string)$response['expireDateTimeAccesToken']);

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
		Log::error('Error occurred in get request.', ['error' => $error]);
	}
}
