<?php

namespace evandroaugusto\Getnet;


class GetnetRoutes
{

    /**
     * Plans routes
     */
    public static function plans()
    {
        $anonymous = new GetnetAnonymous();

        $anonymous->base = static function () {
            return 'v1/plans';
        };

        $anonymous->details = static function ($planId) {
            return "v1/plans/{$planId}";
        };

        $anonymous->status = static function ($planId, $status) {
            return "v1/plans/{$planId}/status/$status";
        };

        return $anonymous;
    }

    /**
     * Clients routes
     */
    public static function clients()
    {
        $anonymous = new GetnetAnonymous();

        $anonymous->base = static function () {
            return 'v1/customers';
        };

        $anonymous->details = static function ($customerId) {
            return "v1/customers/{$customerId}";
        };

        return $anonymous;
    }

    /**
     * Subscriptions routes
     * @return [type] [description]
     */
    public static function subscriptions()
    {
        $anonymous = new GetnetAnonymous();

        $anonymous->base = static function () {
            return 'v1/subscriptions';
        };

        $anonymous->details = static function ($id) {
            return "v1/subscriptions/{$id}";
        };

        $anonymous->charges = static function () {
            return 'v1/charges';
        };

        $anonymous->chargesProjection = static function ($subscriptionId) {
            return "v1/subscriptions/{subscriptionId}/charges/projection";
        };

        return $anonymous;
    }
}
