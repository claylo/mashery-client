<?php
namespace MasheryApi;
use MasheryApi\Wrapper;

class Key extends Wrapper
{
    public function fetch($service_key, $apikey)
    {
        $resp = $this->call('key.fetch', array(array(
            'service_key' => $service_key,
            'apikey' => $apikey
        )));
        
        if (! $resp) {
            // handle error
        }
        return $resp['result'];
        
    }
}