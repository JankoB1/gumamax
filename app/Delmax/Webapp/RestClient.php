<?php namespace Delmax\Webapp;

/**
 * Created by JetBrains PhpStorm.
 * User: nikola
 * Date: 9/21/13
 * Time: 11:42 PM
 */
use Exception;
use Illuminate\Support\Facades\Log;

class RestClient {

// Initialize options for REST interface
    private $api_url;
    private $api_curl_handle;
    private $username;
    private $password;
    private $keep_alive_uri;
    private $bearer_token;
    private $api_curl_option_defaults = array(
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 5,
        CURLOPT_ENCODING =>'UTF-8',
        CURLOPT_SSL_VERIFYPEER => false
    );

    public function __construct($url, $username='', $password='', $keep_alive_uri='', $bearer_token=''){

        if (env('TEST_MERCHANT_API_URL','')!=''){
            $this->api_url =  env('TEST_MERCHANT_API_URL','');
            $this->username = env('TEST_MERCHANT_API_USERNAME', '');
            $this->password = env('TEST_MERCHANT_API_PASSWORD', '');
            $this->keep_alive_uri = env('TEST_MERCHANT_API_KEEPALIVE', '');
        }
        else
        {
            $this->api_url =  $url;
            $this->username = $username;
            $this->password = $password;
            $this->keep_alive_uri = $keep_alive_uri;
            $this->bearer_token = $bearer_token;
        }
    }


    // REST function.
    // Connection are created demand and closed by PHP on exit.
    private function restCall($method, $uri, $query=NULL, $jsonData=NULL){

        // Connect
        $this->api_curl_handle = curl_init();
        $query = (is_null($query)||($query===''))?'':'?'.$query;
        // Compose querry
        $options = [
            CURLOPT_URL => $this->api_url.$uri.$query,
            CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS
            CURLOPT_POSTFIELDS => json_encode($jsonData, true),
        ];
        //Basic-auth

        $authOptions = [];
        if (($this->username!='')&&($this->password!='')){
            $authOptions = [CURLOPT_USERPWD => $this->username . ":" . $this->password];
        } elseif ($this->bearer_token != '') {
            $authorization = "Authorization: Bearer ". $this->bearer_token;
            $authOptions = [CURLOPT_HTTPHEADER => ['Content-Type: application/json', $authorization]];
        }

        $curl_options = ($options + $this->api_curl_option_defaults + $authOptions);
        curl_setopt_array($this->api_curl_handle, $curl_options);

        // send request and wait for response
        $response =  curl_exec($this->api_curl_handle);
        $result_info = curl_getinfo($this->api_curl_handle);

        //Error handler
        if ($result_info['http_code'] != 200 && $result_info['http_code'] != 201) {
            $msg = curl_error($this->api_curl_handle) ? curl_error($this->api_curl_handle) : 'Response: ' . $response;
            curl_close($this->api_curl_handle);
            throw new RestClientException($msg, $result_info['http_code']);
        }

        curl_close($this->api_curl_handle);

        return $response;
    }


    public function postCall($uri, $query=NULL, $jsonData=NULL){

        return $this->restCall('POST', $uri, $query, $jsonData);
    }

    public function getCall($uri, $query=NULL, $jsonData=NULL){
        return $this->restCall('GET', $uri, $query, $jsonData);
    }

    public function putCall($uri, $query=NULL, $jsonData=NULL){
        return $this->restCall('PUT', $uri, $query, $jsonData);
    }

    public function deleteCall($uri, $query=NULL, $jsonData=NULL){
        return $this->restCall('DELETE', $uri, $query, $jsonData);
    }

    public function isAlive(){

        if ($this->keep_alive_uri!=''){
            try
            {
                $this->getCall($this->keep_alive_uri);
                return true;
            }catch (Exception $e){
                return false;
            }
        }

        return true;
    }
}

/**
 * RestClient Exception Handler
 *
 * @package RestAPI
 */
class RestClientException extends \Exception {

    /**
     * Constructor
     *
     * @param string $message Error message
     * @param int $code Error code
     * @param Exception $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}