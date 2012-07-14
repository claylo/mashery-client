<?php
/**
 * Mashery API Client Wrapper
 * 
 * PHP version 5.3+
 * 
 * @category  MasheryAPI
 * @package   MasheryAPI\Message
 * @author    Clay Loveless <clay@php.net>
 * @copyright 2012 Clay Loveless
 * @license   MIT License
 */
namespace MasheryApi;

class Wrapper
{
    /**
     * API configuration pulled from config.json file
     */
    protected $_config = array();
    
    public $error_code = null;
    public $error_message = '';
    public $last_response;
    public $last_info;
    public $last_curl_errno;
    public $last_curl_error;
    public $log_handle;
    public $log = false;
    
    /**
     * Instantiate, test for required extensions, and load specified config.
     * 
     * @param string $config_file_path Path to config JSON file
     * @param string $api_env One of 'sandbox' or 'live'
     */
    public function __construct($config_file_path, $api_env = 'sandbox')
    {
        if (! function_exists('curl_init')) {
            throw new \RuntimeException('cURL extension required.');
        }
        
        if (! file_exists($config_file_path)) {
            throw new \RuntimeException(
                'config file not found at: ' . $config_file_path
            );
        }
        
        $conf = json_decode(file_get_contents($config_file_path), true);
        
        
        switch ($api_env) {
            case 'live':
            case 'production':
                $env_key = 'live';
                break;
            default:
                $env_key = 'sandbox';
        }
        
        if (!isset($conf[$env_key])) {
            throw new \RuntimeException("'$api_env' configuration not found");
        }
        $this->_config = $conf[$env_key];
    }
    
    public function logTo($log_file_path)
    {
        $this->log_handle = fopen($log_file_path, 'a');
        if ($this->log_handle) {
            $this->log = true;
        }
        return $this;
    }
    
    public function log($msg)
    {
        date_default_timezone_set('UTC');
        if ($this->log && is_resource($this->log_handle)) {
            fwrite($this->log_handle, '['.date(DATE_RFC822) . "] $msg\n");
        }
    }
    
    public function call($method, $parameters = null, $return_error = false)
    {
        if ($parameters === null) {
            $parameters = array();
        }
        if (! is_array($parameters)) {
            $parameters = array((string) $parameters);
        }
        
        // Not supporting async calls right now
        $call = array(
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $parameters,
            'id' => 1
        );
        
        $this->error_code = null;
        $this->error_message = '';
        
        $req = json_encode($call);

        $this->log(__METHOD__." request body");
        $this->log($req);
        $response = $this->_post($this->_getEndpointUrl(), $req);
        $this->log(__METHOD__." response body");
        $this->log($response);
        if ($this->last_curl_errno != CURLE_OK) {
            if ($return_error) {
                $resp = array();
                $resp['error'] = array(
                    'code' => $this->last_curl_errno,
                    'message' => $this->last_curl_error
                );
                return $resp;
            }
            return false;
        }

        $resp = json_decode($response, true);
        $json_error = json_last_error();
        if ($json_error === JSON_ERROR_NONE) {
            if (isset($resp['error'])) {
                $this->error_code = $resp['error']['code'];
                $this->error_message = $resp['error']['message'];
                if ($return_error) {
                    return $resp;
                }
                return false;
            }            
        } else {
            if ($return_error) {
                switch ($json_error) {
                    case JSON_ERROR_DEPTH:
                        $msg = 'The maximum JSON stack depth exceeded.';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $msg = 'Invalid or malformed JSON.';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $msg = 'Control character error, possibly incorrectly encoded.';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $msg = 'JSON Syntax Error';
                        break;
                    case JSON_ERROR_UTF8:
                        $msg = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
                        break;
                    default:
                        $msg = 'Not a JSON response.';
                }
                $resp = array();
                $resp['error'] = array(
                    'code' => $this->last_info['http_code'],
                    'message' => $msg
                );
                return $resp;
            }
            return false;
        }
        return $resp;
    }
    
    /**
     * Perform a JSON-RPC POST to the Mashery API using cURL.
     * 
     * @param string $url Fully qualified URL for the request, with signature.
     * @param string $data Ready-to-go data to send in the post body.
     * @return string
     */
    protected function _post($url, $data)
    {
        $ch = curl_init();
        
        // use CUSTOMREQUEST to bypass the automatic form encoding.
        $opts = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_CONNECTTIMEOUT  => 2,
            CURLOPT_TIMEOUT         => 10,
            CURLOPT_CUSTOMREQUEST   => 'POST',
            CURLOPT_USERAGENT       => 'MasheryApi_Client/1.0',
            CURLOPT_HTTPHEADER      => array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data) . "\r\n",
                $data
            )
        );
        curl_setopt_array($ch, $opts);
        $this->last_response = curl_exec($ch);
        $this->last_info = curl_getinfo($ch);
        $this->last_curl_errno = curl_errno($ch);
        $this->last_curl_error = curl_error($ch);
        curl_close($ch);
        return $this->last_response;
    }
    
    public function getLastResponseInfo()
    {
        return $this->last_info;
    }
    /**
     * Returns a signed endpoint URL
     * 
     * @return string
     */
    protected function _getEndpointUrl()
    {
        $c = $this->_config;
        $auth = array();
        $auth['apikey'] = $c['key'];
        $auth['sig'] = hash('md5',  $c['key'] . $c['secret'] . gmdate('U'));
        $q = http_build_query($auth);
        
        return 'http://' . $c['host'] . $c['path'] . '?' . $q;
    }
}