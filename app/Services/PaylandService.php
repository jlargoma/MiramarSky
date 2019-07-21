<?php

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PaylandService
{
    const METHOD_POST = "POST";
    const METHOD_GET  = "GET";
    const FORMAT      = "json";

    protected      $client;
    protected      $api;
    protected      $apiKey;
    protected      $secretKey;
    protected      $ssl;
    private static $PAYMENT_URL      = "/payment";
    private static $PROCCESS_PAYMENT = "/payment/process/";

    public function __construct($config)
    {
        $this->api       = $config['endpoint'];
        $this->apiKey    = $config['api_key'];
        $this->secretKey = $config['signarute'];
        $this->ssl       = env('CURL_CERT');
        $this->client    = new Client([
                                          "headers" => [
                                              'Authorization' => 'Bearer ' . $this->apiKey,
                                              'Accept'        => 'application/json',
                                          ]
                                      ]);
    }

    public function call($method, $endpoint, $params = [])
    {
        if ($method === self::METHOD_GET)
        {
            $url     = $this->api . $endpoint . '?' . http_build_query($params);
            $options = $params;
        } else
        {
            $url             = $this->api . $endpoint;
            $options['json'] = $params;

        }
        return $this->doRequest($method, $url, $options);
    }

    /**
     * @param      $method
     * @param      $urlBase
     * @param      $endpoint
     * @param      $headers
     * @param null $body
     * @param null $query
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws RequestException
     */
    public function doRequest($method, $url, $options = [])
    {
        $code = null;
        try
        {
            $response = $this->client->request($method, $url, $options);
            $code     = $response->getStatusCode();
            $response = (string) $response->getBody();
            $response = \GuzzleHttp\json_decode($response);
            $response = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($response), FALSE);

            return $response;
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function payment(array $params)
    {
        try
        {
            $response = $this->call(self::METHOD_POST, self::$PAYMENT_URL, $params);
            return $response;
        } catch (\Exception $e)
        {
            echo($e->getMessage());
            die();
        }
    }

    public function processPayment($orderToken)
    {
        //dump($this->api. self::$PROCCESS_PAYMENT . $orderToken);
        return $this->api. self::$PROCCESS_PAYMENT . $orderToken;
    }
}