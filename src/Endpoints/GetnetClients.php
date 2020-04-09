<?php

namespace evandroaugusto\Getnet\Endpoints;

use evandroaugusto\Getnet\Endpoints\GetnetEndpoints;
use evandroaugusto\Getnet\GetnetRoutes as Routes;


class GetnetClients extends GetnetEndpoints
{

    /**
     * Create client
     *
     * @param  array $params
     * @return httpClient
     */
    public function createClient($params)
    {
        $required = [
            'customer_id',
            'first_name',
            'last_name',
            'email',
            'document_number'
        ];

        // validate required parameters
        $this->validateParameters($params, $required);

        // prepare post values
        $post = [
            'seller_id'       => $this->getnet->getSellerId(),
            'customer_id'     => $params['customer_id'],
            'first_name'      => $params['first_name'],
            'last_name'       => $params['last_name'],
            'email'           => $params['email'],
            'document_type'   => $params['document_type'] ?? 'CPF',
            'document_number' => $params['document_number'],
            'birth_date'      => $params['birth_date'] ?? null,
            'phone_number'    => $params['phone_number'] ?? null,
            'observation'     => $params['observation'] ?? null
        ];

        // make http request
        return $this->getnet->makeRequest(
            'POST',
            Routes::clients()->base(),
            $post
        );
    }

    /**
     * Fetch all clients
     *
     * @param  array $params
     * @return httpClient
     */
    public function fetchClients($params=[])
    {
        // prepare default values
        $query = [
            'page' => isset($params['page']) ? (int) $params['page'] : 1,
            'limit' => isset($params['page']) ? (int) $params['limit'] : 20,
            'sort' => $params['sort'] ?? 'first_name',
            'sort_type' => $params['sort_type'] ?? 'asc',
            'document_number' => $params['document_number'] ?? null,
            'customer_id' => $params['customer_id'] ?? null
        ];

        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::clients()->base(),
            $query
        );
    }

    /**
     * Fetch a client
     *
     * @param  array $params
     * @return httpClient
     */
    public function fetchClient($params)
    {
        // prepare default values
        if (!isset($params['customer_id'])) {
            throw new \Exception('Missing customer ID', 1);
        }

        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::clients()->details($params['customer_id'])
        );
    }
}
