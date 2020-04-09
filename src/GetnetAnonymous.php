<?php

namespace evandroaugusto\Getnet;


class GetnetAnonymous extends \StdClass
{

    /**
     * @param string $methodName
     * @param array $params
     */
    public function __call($methodName, $params)
    {
        // check if exist method
        if (!isset($this->{$methodName})) {
            throw new \Exception('Call to undefined method ' . __CLASS__ . '::' . $methodName . '()');
        }

        // return closure setting __invoke
        return $this->{$methodName}->__invoke(... $params);
    }
}
