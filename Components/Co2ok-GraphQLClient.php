<?php
namespace co2ok_plugin_woocommerce\Components;

class Co2ok_GraphQLClient extends Co2ok_HttpsRequest
{
    public function __construct($apiUrl)
    {
        parent::__construct($apiUrl);
    }

    public function query($callback,$responseCallback)
    {
        $requestType = "query";
        $query = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLRequest($requestType);
        $callback($query);
        $query->ProcessQuery($requestType);

        $response = $this->executeRequest($query->requestQuery);
        $responseCallback($response);
    }

    public function mutation($callback,$responseCallback)
    {
        $requestType = "mutation";
        $mutation = new \co2ok_plugin_woocommerce\Components\Co2ok_GraphQLRequest($requestType);
        $callback($mutation);
        $mutation->ProcessQuery($requestType);

        $response = $this->executeRequest($mutation->requestQuery);
        $responseCallback($response);
    }
}