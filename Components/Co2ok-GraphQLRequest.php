<?php
namespace co2ok_plugin_woocommerce\Components;

Class Co2ok_GraphQLMutation
{
    private $mutationFunctionName;
    private $mutationFunctionParams;
    private $mutationFunctionReturnTypes;

    public $mutationQuery;

    public function __construct()
    {
        $this->mutationQuery = "mutation { ";
    }

    public function setFunctionName($functionName)
    {
        $this->mutationFunctionName = $functionName;
    }

    public function setFunctionParams($functionParams)
    {
        $this->mutationFunctionParams = $functionParams;
    }

    public function setFunctionReturnTypes($functionReturnTypes)
    {
        $this->mutationFunctionReturnTypes = $functionReturnTypes;
    }

    public function ProcessMutationQuery()
    {
        $this->mutationQuery = "mutation { ";
        $this->mutationQuery .= $this->mutationFunctionName.'(';

        $paramCount = count($this->mutationFunctionParams);
        $paramIndex = 0;
        foreach($this->mutationFunctionParams as $key => $param)
        {
            if(!is_numeric($param))
                $this->mutationQuery .= $key.': "'.$param.'"';
            else
                $this->mutationQuery .= $key.': '.$param.'';
            $paramIndex++;

            if($paramIndex < $paramCount)
                $this->mutationQuery .= ", ";
        }

        $this->mutationQuery .= ') { ';
        $returnTypeCount = count($this->mutationFunctionParams);
        $returnTypeIndex = 0;
        foreach($this->mutationFunctionReturnTypes as $key => $param)
        {
            if(is_array($param))
            {
                $returnFunc = preg_replace('/"/', '',  json_encode($param) );
                $returnFunc = preg_replace('/\[/', '{',  $returnFunc );
                $returnFunc = preg_replace('/\]/', '}',  $returnFunc );

                $this->mutationQuery .= $key .  $returnFunc;
            }
            else
            {
                $this->mutationQuery .= $param;
            }

            $paramIndex++;

            if($paramIndex < $paramCount)
                $this->mutationQuery .= ", ";
        }
        $this->mutationQuery .= '}'; //Closes function

        $this->mutationQuery .= '}'; // Closes mutation
    }
}