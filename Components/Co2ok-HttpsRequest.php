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

        return $http->post(  $this->url.'?query='.urlencode($body), array( ) );
    }   
}