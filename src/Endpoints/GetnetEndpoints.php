<?php

namespace evandroaugusto\Getnet\Endpoints;

use evandroaugusto\Getnet;


class GetnetEndpoints
{
    protected $getnet;
    protected $http;


    public function __construct(Getnet\Getnet $getnet)
    {
        $this->getnet = $getnet;
        $this->http 	= $getnet->getHttp();
    }


    /**
     * Validate paramenters
     * @param  array $params
     * @param  array $required
     * @return boolean
     */
    protected function validateParameters(array $params, array $required): bool
    {
        if (!$params) {
            throw new \InvalidArgumentException("Missing parameters", 1);
        }

        $errors = [];

        foreach ($required as $req) {
            if (!isset($params[$req])) {
                $errors[] = $req;
            }
        }

        // check errors
        if ($errors) {
            throw new \InvalidArgumentException("Missing parameters: " . implode(',', $errors), 1);
        }

        return true;
    }
}
