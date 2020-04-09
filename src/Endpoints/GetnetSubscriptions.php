<?php

namespace evandroaugusto\Getnet\Endpoints;

use evandroaugusto\Getnet\GetnetRoutes as Routes;

class GetnetSubscriptions extends GetnetEndpoints
{

    /**
     * Fetch user subscriptions
     * @param  array $params
     * @return httpClient
     */
    public function fetchSubscriptions($params)
    {
        // prepare default values
        $query = [
            'page'  => (int) $params['page'] ?? 1,
            'limit' => (int) $params['limit'] ?? 20,
            'sort'  => $params['sort'] ?? 'create_date',
            'sort_type' => $params['sort_type'] ?? 'desc'
        ];

        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::subscriptions()->base(),
            $query
        );
    }

    /**
     * Fetch a specific subscription
     * @param  array $params
     * @return httpClient
     */
    public function fetchSubscription($params)
    {
        if (!isset($params['subscription_id'])) {
            throw new \Exception('Missing subscription_id', 1);
        }

        $subscriptionId = $params['subscription_id'];

        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::subscriptions()->details($subscriptionId)
        );
    }

    /**
     * Fetch charges projection from a subscription
     * @param  array $params
     * @return httpClient
     */
    public function fetchChargesProjection($params)
    {
        // default querystring
        $query = [
            'limit' => (int) $params['limit'] ?? 20
        ];

        $subscriptionId = $params['subscription_id'];

        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::subscriptions()->chargesProjection($subscriptionId),
            $query
        );
    }

    /**
     * Fetch all charges in list
     */
    public function fetchCharges($params)
    {
        // default querystring
        $query = [
            'page' => (int) $params['page'] ?? 1,
            'limit' => (int) $params['limit'] ?? 20
        ];


        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::subscriptions()->charges(),
            $query
        );
    }
}
