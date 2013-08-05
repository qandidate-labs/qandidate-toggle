<?php

namespace Qandidate\Trigger\ToggleCollection;

use Qandidate\Trigger\Toggle;
use Qandidate\Trigger\ToggleCollection;

/**
 * In memory collection useful for testing or when toggles are loaded
 * "statically" from for example configuration.
 */
class InMemoryCollection extends ToggleCollection
{
    private $toggles = array();

    /**
     * {@inheritDoc}
     */
    public function get($name)
    {
        if ( ! array_key_exists($name, $this->toggles)) {
            return null;
        }

        return $this->toggles[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function set($name, Toggle $toggle)
    {
        $this->toggles[$name] = $toggle;
    }

    /**
     * {@inheritDoc}
     */
    public function remove($name)
    {
        if ( ! array_key_exists($name, $this->toggles)) {
            return;
        }

        unset($this->toggles[$name]);
    }
}
