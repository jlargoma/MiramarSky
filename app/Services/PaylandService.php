<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PaylandService {

  const METHOD_POST = "POST";
  const METHOD_GET = "GET";
  const FORMAT = "json";

  protected $client;
  protected $api;
  protected $apiKey;
  protected $secretKey;
  protected $ssl;
  private static $PAYMENT_URL = "/payment";
  private static $PROCCESS_PAYMENT = "/payment/process/";

  public function __construct($config) {
    $this->api = $config['endpoint'];
    $this->apiKey = $config['api_key'];
    $this->secretKey = $config['signarute'];
    $this->ssl = env('CURL_CERT');
    $this->client = new Client([
        "headers" => [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ]
    ]);
  }

  public function call($method, $endpoint, $params = []) {
    if ($method === self::METHOD_GET) {
      $url = $this->api . $endpoint . '?' . http_build_query($params);
      $options = $params;
    } else {
      $url = $this->api . $endpoint;
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
  public function doRequest($method, $url, $options = []) {
    $code = null;
    try {
      $response = $this->client->request($method, $url, $options);

      $code = $response->getStatusCode();
      $response = (string) $response->getBody();
      $response = \GuzzleHttp\json_decode($response);
      $response = \GuzzleHttp\json_decode(\GuzzleHttp\json_encode($response), FALSE);

      return $response;
    } catch (\Exception $e) {
//          dd($e->getMessage());
      throw new \Exception($e->getMessage());
    }
  }

  public function payment(array $params) {
    $params['secure'] = false;
    try {
      $response = $this->call(self::METHOD_POST, self::$PAYMENT_URL, $params);
      return $response;
    } catch (\Exception $e) {
      return null;
    }
  }

  public function processPayment($orderToken) {
    //dump($this->api. self::$PROCCESS_PAYMENT . $orderToken);
    return $this->api . self::$PROCCESS_PAYMENT . $orderToken;
  }

  public function getOrders($startDate, $endDate) {
    $params['secure'] = false;
    try {
      $params = [
          'start' => $startDate,
          'end' => $endDate,
          'terminal' => env('PAYLAND_TERMINAL')
      ];
      //readonly
      $response = $this->call(self::METHOD_GET, '/orders', $params);
      return $response;
    } catch (\Exception $e) {
      return null;
    }
  }

  public function getCurrency($currencyID) {
    if ($currencyID == 978) {
      return 'EUR';
    }

    return '$';
  }

  public function confirmationPayment($params) {
    try {
      $response = $this->call(self::METHOD_POST, '/payment/confirmation', $params);
      return $response;
    } catch (\Exception $e) {
      $response = $e->getMessage();

      $aux = explode('"details":', $response);
      if (is_array($aux) && isset($aux[1])) {
        return str_replace('}', '', $aux[1]);
      }

      return $response;
    }
  }

  /**
   * Create link to new Payland
   * 
   * @param type $bookingID
   * @param type $clientID
   * @param type $client_email
   * @param type $description
   * @param type $amount
   * @return type
   */
  public function generateOrderPaymentBooking($bookingID, $clientID, $client_email, $description, $amount, $is_deferred = false) {

    $key_token = md5($bookingID . '-' . time() . '-' . $clientID);
    $type = $is_deferred ? 'DEFERRED' : 'AUTHORIZATION';
    $amount = ($amount * 100); // esto hay que revisar
    $response['_token'] = null;
    $response['amount'] = $amount;
    $response['customer_ext_id'] = $client_email;
    $response['operative'] = $type;
    $response['secure'] = false;
    $response['signature'] = env('PAYLAND_SIGNATURE');
    $response['service'] = env('PAYLAND_SERVICE');
    $response['description'] = $description;
    $response['url_ok'] = route('payland.thanks.payment', $key_token);
    $response['url_ko'] = route('payland.error.payment', $key_token);
    $response['url_post'] = route('payland.process.payment', $key_token);

    if ($is_deferred)
      $response['url_ok'] = route('payland.thanks.deferred', $key_token);

    
  
    $orderPayment = $this->payment($response);

    if ($is_deferred)
      $BookOrders = new \App\BookDeferred();
    else
      $BookOrders = new \App\BookOrders();

    $BookOrders->book_id = $bookingID;
    $BookOrders->cli_id = $clientID;
    $BookOrders->cli_email = $client_email;
    $BookOrders->subject = $description;
    $BookOrders->key_token = $key_token;
    $BookOrders->order_uuid = $orderPayment->order->uuid;
    $BookOrders->order_created = $orderPayment->order->created;
    $BookOrders->amount = $orderPayment->order->amount;
    $BookOrders->refunded = $orderPayment->order->refunded;
    $BookOrders->currency = $orderPayment->order->currency;
    $BookOrders->additional = $orderPayment->order->additional;
    $BookOrders->service = $orderPayment->order->service;
    $BookOrders->status = $orderPayment->order->status;
    $BookOrders->token = $orderPayment->order->token;
    $BookOrders->transactions = json_encode($orderPayment->order->transactions);
    $BookOrders->client_uuid = $orderPayment->client->uuid;
    $BookOrders->is_deferred = $is_deferred;
    $bo_id = $BookOrders->save();


    $urlToRedirect = $this->processPayment($orderPayment->order->token);
    return $urlToRedirect;
  }

}
