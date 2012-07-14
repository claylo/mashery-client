<?php
namespace MasheryApi;

use MasheryApi\Wrapper;

class Object extends Wrapper
{
    public function __call($method, $arg = null)
    {
        if ($method == 'list') {
            return $this->dolist($arg);
        }
    }
    
    public function dolist()
    {
        $resp = $this->call('object.list');
        if (! $resp) {
            // handle error
        }
        return $resp['result'];
        
    }
    
    public function describe($types, $locale = 'en_US')
    {
        if (is_array($types)) {
            $desc_types = $types;
        } else if (is_string($types)) {
            $desc_types = explode(',', $types);
        }
        
        $resp = $this->call('object.describe', array(
            $desc_types,
            $locale
        ));
        
        if (! $resp) {
            // handle error
        }
        return $resp['result'];
    }
}