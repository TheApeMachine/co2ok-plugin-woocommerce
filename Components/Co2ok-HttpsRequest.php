<?php
namespace co2ok_plugin_woocommerce\Components;

class Co2ok_HttpsRequest
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function executeRequest($body)
    {
            $http = _wp_http_get_object();

            $tries = 4;
            $sleep = 1; 
            do {
                $response = $http->post(  $this->url.'?query='.urlencode($body), array( ) );
                if (200 == wp_remote_retrieve_response_code($response)) {
                    // Looks good!
                    break;
                } else {
                    // printf("\nInvalid response from API, sleeping %d seconds and trying again...\n", intval($sleep));
                    sleep($sleep);
                    ++$sleep;
                }
            } while (--$tries);
            if (200 != wp_remote_retrieve_response_code($response)) {
                // This would be the place to do remote logging
                // not exit though: calling functions expect the JSON response
                // exit('Could not connect to API server.');
            }

        return $response;
    }   
}