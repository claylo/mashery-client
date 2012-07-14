<?php
namespace MasheryApi\OAuth2;

/**
 * URI Object wrapper for Mashery OAuth 2.0 API
 * 
 */
class Uri
{
    public $redirect_uri = null;
    public $state = null;
    
    public function __construct($uri_string)
    {
        
    }
    
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }
    
    public function setRedirectUri($redirect_uri = null)
    {
        $this->redirect_uri = $redirect_uri;
        return $this;
    }
    
    public function getState()
    {
        return $this->state;
    }
    
    public function setState($state = null)
    {
        $this->state = $state;
        return $this;
    }
}