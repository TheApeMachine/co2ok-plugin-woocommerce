<?php
/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 9/6/2017
 * Time: 20:39 PM
 */


class HttpsRequest
{
    private $url;
    private $ch;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function executeRequest($body)
    {
        $this->url = $this->url.'?query='.urlencode($body);
        $this->ch = curl_init( $this->url );
        curl_setopt( $this->ch, CURLOPT_POSTFIELDS, array() );
        curl_setopt(  $this->ch , CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec( $this->ch );
        curl_close( $this->ch );

        return $result;
    }   
}