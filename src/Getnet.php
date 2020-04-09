<?php

namespace evandroaugusto\Getnet;

use evandroaugusto\HttpClient\HttpClient;
use evandroaugusto\Getnet\Endpoints;


class Getnet
{
    private $clientId;
    private $clientSecret;
    private $sellerId;

    // defaults
    private $isDev;
    private $accessToken;

    // endpoints
    private $http;

    private $plans;
    private $clients;
    private $subscriptions;


    public function __construct(
        $clientId,
        $clientSecret,
        $sellerId
    ) {
        // validate parameters
        if (!isset($clientId, $clientSecretId, $sellerId)) {
            throw new \Exception("Missing credential parameters");
        }

        // set credentials
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->sellerId	= $sellerId;

        // set defaults
        $this->isDev = false;

        // set http request
        $this->http = new GetnetHttpRequest($this);
        
        // set endpoints
        $this->auth = new Endpoints\GetnetAuth($this);
        $this->plans = new Endpoints\GetnetPlans($this);
        $this->clients = new Endpoints\GetnetClients($this);
        $this->subscriptions = new Endpoints\GetnetSubscriptions($this);
    }

        
    /**
     * Authenticate Getnet account
     *
     * @return string string
     */
    public function authenticate($authString=null)
    {
        if (!$authString) {
            $authString = $this->getAuthString();
        }
        
        // authenticate account
        $auth = $this->auth->authenticate($authString);

        // validate authentication
        if (isset($auth->error)) {
            throw new \Exception('[gateway]' . $auth->error . ': ' . $auth->error_description, 1);
        }
    
        // set access token
        $this->setAccessToken($auth->access_token);

        return $auth;
    }

    /**
     * Make HTTP request
     *
     * @param  string $verb
     * @param  string $url
     * @param  array  $fields
     * @return array
     */
    public function makeRequest($verb, $url, $fields=[])
    {
        // check if user is authenticated
        if (!$this->getAccessToken()) {
            $this->authenticate();
        }

        // make http request
        $request = $this->http->makeRequest($verb, $url, $fields);

        // return in json response
        $request = json_decode($request);

        // Intercept return and validate result
        if (isset($request->status_code)) {
            if ($request->status_code == '404') {
                return null;
            }

            // error processing request
            throw new \Exception($request->message . ': ' . $request->name, 1);
        }

        return $request;
    }

    //
    // Getters / Setters
    //
        
    public function plans()
    {
        return $this->plans;
    }

    public function clients()
    {
        return $this->clients;
    }

    public function subscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * Return endopoint based on enviroment
     * 
     * @return string
     */
    public function getEndpoint()
    {
        return $this->http->getEndpoint();
    }

    /**
     * Get HTTP Client
     * 
     * @return Client Http
     */
    public function getHttp(): HttpClient
    {
        return $this->http->getHttp();
    }

    /**
     * Get access token
     * 
     * @return [type] [description]
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set access token
     * 
     * @param string $accessToken
     */
    public function setAccessToken($accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get AuthString (CliendId + ClientSecret)
     * 
     * @return string
     */
    public function getAuthString(): string
    {
        return base64_encode($this->clientId . ':' . $this->clientSecret);
    }

    /**
     * Get sellerId
     * 
     * @return string
     */
    public function getSellerId(): string
    {
        return $this->sellerId;
    }

    /**
     * Enable development mode
     * 
     * @return void
     */
    public function enableDev(): void
    {
        $this->isDev = true;
    }

    /**
     * Disable development mode
     * 
     * @return void
     */
    public function disableDev(): void
    {
        $this->isDev = false;
    }

    /**
     * Set dev environment
     * 
     * @param bool $isDev
     */
    public function setDev($isDev): void
    {
        $this->isDev = (bool) $isDev;
    }

    /**
     * Get dev status
     * 
     * @return string
     */
    public function getIsDev(): string
    {
        return $this->isDev;
    }
}
