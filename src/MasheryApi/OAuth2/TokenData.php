<?php
namespace MasheryApi\OAuth2;

/**
 * URI Object wrapper for Mashery OAuth 2.0 API
 * 
 */
class TokenData
{
    public $grant_type = null;
    public $scope = null;
    public $code = null;
    public $response_type = null;
    public $refresh_token = null;
    
    public function getGrantType()
    {
        return $this->grant_type;
    }
    
    public function setGrantType($grant_type)
    {
        $grant_type = strtolower($grant_type);
        switch ($grant_type) {
            case 'authorization_code':
            case 'password':
            case 'client_credentials':
            case 'refresh_token':
                $this->grant_type = $grant_type;
                break;
            default: 
                $this->grant_type = 'authorization_code';
        }
        return $this;
    }
    
    public function getScope()
    {
        return $this->scope;
    }
    
    public function setScope($scope = null)
    {
        $this->scope = $scope;
        return $this;
    }
    
    public function getCode()
    {
        return $this->code;
    }
    
    public function setCode($code)
    {
        if (is_string($code)) {
            $this->code = $code;
        } elseif (is_object($code)) {
            $this->code = $code->getCode();
        }
        return $this;
    }
    
    public function getResponseType()
    {
        return $this->response_type;
    }
    
    public function setResponseType($response_type = null)
    {
        if ($response_type == 'code') {
            $this->response_type = 'code';
        } elseif ($response_type = 'token') {
            $this->response_type = 'token';
        } else {
            $this->response_type = null;
        }
        return $this;
    }
    
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }
    
    public function setRefreshToken($refresh_token = null)
    {
        $this->refresh_token = $refresh_token;
        return $this;
    }
    
    public function asArray()
    {
        return array(
            'grant_type'    => $this->grant_type,
            'scope'         => $this->scope,
            'code'          => $this->code,
            'response_type' => $this->response_type,
            'refresh_token' => $this->refresh_token
        );
    }
}