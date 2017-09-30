<?php
namespace co2ok_plugin_woocommerce\Components;

class Co2ok_GraphQLClient extends Co2ok_HttpsRequest
{
    public function __construct($apiUrl)
    {
        parent::__construct($apiUrl);
    }

    public function query()
    {
    }

    public function mutation($callback,$responseCallback)
    {
        $mutation = new Co2ok_GraphQLMutation();
        $callback($mutation);
        $mutation->ProcessMutationQuery();
        
        $response = $this->executeRequest($mutation->mutationQuery);
        $responseCallback($response);
    }
}