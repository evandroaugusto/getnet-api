<?php

namespace evandroaugusto\Getnet;

use evandroaugusto\Getnet\HttpClient\HttpClient;


class GetnetHttpRequest
{
    const endpointTpl = 'https://%s.getnet.com.br';
        
    private $getnet;
    private $http;


    //
    // Constructor
    //

    public function __construct(Getnet $getnet)
    {
        $this->getnet = $getnet;
        $this->http = new HttpClient();
    }

    //
    // Public mehthods
    //

    /**
     * Make HTTP request to Getnet
     */
    public function makeRequest($verb, $url, $fields=[])
    {
        // validate parameters
        if (!$verb || !$url) {
            throw new \Exception('Missing parameters', 1);
        }

        if (!$this->getnet->getAccessToken()) {
            throw new \Exception('Getnet: Invalid access token', 1);
        }

        // set default header
        $attr = [];
        $attr['header'] = array(
            'Authorization: Bearer ' . $this->getnet->getAccessToken(),
            'seller_id: ' . $this->getnet->getSellerId()
        );

        // full url
        $url = $this->getEndpoint() . '/' . $url;

        // validate HTTP resource
        switch ($verb) {
            case 'POST':
            case 'PATCH':
                // validate POST fields
                if (!isset($fields) || !is_array($fields)) {
                    throw new \Exception("Missing parameters fields");
                }

                // header request
                $attr['header'] = array_merge($attr['header'], [
                        'Content-Type: application/json; charset=utf-8',
                        'username: spotfinder-api'
                ]);

                // prepare values
                $jsonFields = json_encode($fields);

                $request = $this->http->makeRequest(
                    $verb,
                    $url,
                    ['header' => $attr['header']],
                    $jsonFields
                );
                break;

            case 'GET':
                $request = $this->http->makeRequest(
                    $verb,
                    $url,
                    ['header'=> $attr['header']],
                    $fields
                );
                break;

            default:
                throw new \Exception("Invalid HTTP verb");
            }

        return $request;
    }

    //
    // Setters and Getters
    //
        
    /**
     * Get endpoint based on class settings
     */
    public function getEndpoint(): string
    {
        $endpoint = null;

        if ($this->getnet->getIsDev()) {
            $endpoint = sprintf(self::endpointTpl, 'api-homologacao');
        } else {
            $endpoint = sprintf(self::endpointTpl, 'api');
        }

        return $endpoint;
    }

    /**
     * Return http client library
     *
     * @return HttpClient
     */
    public function getHttp(): HttpClient
    {
        return $this->http;
    }
}
