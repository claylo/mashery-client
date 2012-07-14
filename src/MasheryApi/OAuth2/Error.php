<?php
namespace MasheryApi\OAuth2;

class Error
{
    public $error;
    public $error_description;
    public $error_response;
    
    public function getError()
    {
        return $this->error;
    }
    
    public function getDescription()
    {
        return $this->error_description;
    }
    
    public function getResponse()
    {
        return $this->error_response;
    }
    
    
}