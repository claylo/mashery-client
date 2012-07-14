<?php
namespace MasheryApi;

use MasheryApi\Wrapper;

class Test extends Wrapper
{
    // since 'echo' is a reserved function name ...
    public function __call($method, $arg)
    {
        if ($method == 'echo') {
            return $this->doecho($arg);
        }
    }
    
    public function doecho($str = "This is a test.")
    {
        $resp = $this->call('test.echo', $str);
        if (! $resp) {
            // handle error
        }
        return $resp['result'];
    }
    
    public function foo()
    {
        $resp = $this->call('test.foo', array('bar'));
        if (! $resp) {
            // handle
            echo "ERROR!\n";
            echo "{$this->error_code}: {$this->error_message}\n";
            return false;
        }
        return $resp['result'];
    }
}