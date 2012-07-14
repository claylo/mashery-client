<?php
namespace MasheryApi\OAuth2;

/**
 * Client object wrapper for Mashery OAuth 2.0 API
 * 
 */
class Client
{
    public $client_id = null;
    public $client_secret = null;
    
    public function __construct($id = null, $secret = null)
    {
        if (is_array($id)) {
            $this->client_id = $id['client_id'];
            $this->client_secret = $id['client_secret'];
        } else {
            $this->client_id = $id;
            $this->client_secret = $secret;
        }
    }
    
    public function getId()
    {
        return $this->client_id;
    }
    
    public function setId($client_id = null)
    {
        $this->client_id = $client_id;
        return $this;
    }
    
    public function getSecret()
    {
        return $this->client_secret;
    }
    
    public function setSecret($client_secret = null)
    {
        $this->client_secret = $client_secret;
        return $this;
    }
    
    public function asArray()
    {
        return array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        );
    }
    
}