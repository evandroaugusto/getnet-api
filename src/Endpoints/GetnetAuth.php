<?php

namespace evandroaugusto\Getnet\Endpoints;


class GetnetAuth extends GetnetEndpoints
{
    // endpoints
    private $apis = [
        'auth' => 'auth/oauth/v2/token',
    ];


    /**
     * Authenticate on GetNet
     */
    public function authenticate($authString)
    {
        if (!$authString) {
            throw new \Exception('Missing authentication ID', 1);
        }

        // prepare POST settings
        $attr = array();
        $attr['header'] = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: Basic ' . $authString
        );
        $attr['fields'] = http_build_query(array(
            'scope'			 => 'oob',
            'grant_type' => 'client_credentials'
        ));

        // POST Request
        $url = $this->getnet->getEndpoint() . '/' . $this->apis['auth'];

        $request = $this->http->makeRequest(
            'POST',
            $url,
            ['header' => $attr['header']],
            $attr['fields']
        );

        return json_decode($request);
    }
}
