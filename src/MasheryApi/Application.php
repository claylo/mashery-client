<?php
namespace MasheryApi;

use MasheryApi\Wrapper;

class Application extends Wrapper
{
    /**
     * The primary identifier for the application object.
     */
    public $id;
    
    public $created;
    public $updated;
    public $username;
    public $name;
    public $description;
    public $type;
    public $commercial;
    public $ads;
    public $ads_system;
    public $usage_model;
    public $notes;
    public $how_did_you_hear;
    public $preferred_protocol;
    public $preferred_output;
    public $external_id = null;
    public $uri;
    public $status;
    public $object_type;
    public $attributes;
    
    public function getId()
    {
        return $this->id;
    }
        
    public function getCreated()
    {
        return $this->created;
    }
    
    public function setCreated($created)
    {
        
    }
    
}