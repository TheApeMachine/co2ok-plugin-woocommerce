<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9/6/2017
 * Time: 20:39 PM
 */

include( plugin_dir_path( __FILE__ ) . '/HttpsRequest.php');
include( plugin_dir_path( __FILE__ ) . '/GraphQLMutation.php');

class GraphQLClient extends HttpsRequest
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
        $mutation = new GraphQLMutation();
        $callback($mutation);
        $mutation->ProcessMutationQuery();
        
        $response = $this->executeRequest($mutation->mutationQuery);
        $responseCallback($response);
    }
}