<?php

namespace evandroaugusto\Getnet\Endpoints;

use evandroaugusto\Getnet\GetnetRoutes as Routes;

class GetnetPlans extends GetnetEndpoints
{

    /**
     * Create a recurrency plan
     */
    public function createPlan($params)
    {
        // validate parameters
        $required = [
            'name',
            'amount',
            'period'
        ];

        $this->validateParameters($params, $required);

        if (!isset($params['period']['billing_cycle'])) {
            throw new \Exception('Missing billing_cycle', 1);
        }

        // prepare post parameters
        $post = [
            'seller_id'     => $this->getnet->getSellerId(),
            'name'          => $params['name'],
            'amount'        => (int) $params['amount'],
            'currency'      => 'BRL',
            'payment_types' => $params['payment_types'] ?? ['credit_card'],
            'period'        => array(
                'type' => $params['period_type'] ?? 'monthly',
                'billing_cycle' => (int) $params['period']['billing_cycle']
            )
        ];

        if (isset($params['sales_taxes'])) {
            $post['sales_taxes'] = (int) $params['sales_taxes'];
        }

        // make http request
        return $this->getnet->makeRequest(
            'POST',
            Routes::plans()->base(),
            $post
        );
    }

    /**
     * List available plans based on given filters
     */
    public function fetchPlans($params=[])
    {
        // prepare default values
        $query = [
            'page' => isset($params['page']) ? (int) $params['page'] : 1,
            'limit' => isset($params['limit']) ? $params['limit'] : 100,
            'sort' => $params['sort'] ?? 'plan_id',
            'sort_type' => $params['sort_type'] ?? 'desc'
        ];

        if (isset($params['name'])) {
            $query['name'] = $params['name'];
        }

        if (isset($params['status'])) {
            $query['status'] = $params['status'];
        }

        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::plans()->base(),
            $query
        );
    }

    /**
     * Fetch a specific plan
     */
    public function fetchPlan($params)
    {
        if (!isset($params['plan_id'])) {
            throw new \Exception('Missing parameters', 1);
        }

        $planId = $params['plan_id'];

        // make http request
        return $this->getnet->makeRequest(
            'GET',
            Routes::plans()->details($planId)
        );
    }


    /**
     * Enable/disable and existing plan
     */
    public function updatePlanStatus($params)
    {
        if (!isset($params['plan_id'])) {
            throw new \Exception('Missing parameter', 1);
        }

        if (!isset($params['status'])) {
            throw new \Exception('Missing parameter', 1);
        }

        $planId = $params['plan_id'];
        $status = $params['status'];

        // make http request
        return $this->getnet->makeRequest(
            'PATCH',
            Routes::plans()->status($planId, $status)
        );
    }
}
