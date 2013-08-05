<?php

namespace Qandidate\Trigger;

/**
 * The context object represents the current state of the application that is
 * important to decide whether a feature is active or not.
 */
class Context
{
    private $values = array();

    public function get($key)
    {
        return $this->values[$key];
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;

        return $this;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->values);
    }
}
