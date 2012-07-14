<?php
namespace MasheryApi;

use MasheryApi\Wrapper;

class OAuth2 extends Wrapper
{
    public function fetchApplication($service_key, $client, $uri = null, $response_type = null)
    {
        if ($response_type != 'token') {
            $response_type = 'code';
        }
                
        
        $resp = $this->call('oauth2.fetchApplication', array(
            'service_key' => $service_key,
            'client' => $client->asArray(),
            'uri' => null,
            'response_type' => $response_type
        ), true);
        return $resp;
    }
    
    public function createAuthorizationCode($service_key, $client, $uri, $scope, $user_context)
    {
        
    }
    
    public function createAccessToken($service_key, $client, $token_data, $uri, $user_context)
    {
        $resp = $this->call('oauth2.createAccessToken', array(
            'service_key' => $service_key,
            'client' => $client->asArray(),
            'token_data' => $token_data->asArray(),
            'uri' => null,
            'user_context' => $user_context
        ), true);
        return $resp;
    }
    
    public function fetchUserApplications($service_key, $user_context)
    {
        
    }
    
    public function revokeAccessToken($service_key, $client, $access_token)
    {
        $resp = $this->call('oauth2.revokeAccessToken', array(
            'service_key' => $service_key,
            'client' => $client->asArray(),
            'access_token' => $access_token
        ), true);
        return $resp;
    }
    
}