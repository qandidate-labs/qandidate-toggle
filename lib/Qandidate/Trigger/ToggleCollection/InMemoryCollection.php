<?php

namespace Qandidate\Trigger\ToggleCollection;

use Qandidate\Trigger\Toggle;
use Qandidate\Trigger\ToggleCollection;

/**
 * A collection of toggles, used by a manager.
 *
 * Abstraction to allow for different storage backends of toggles (e.g. redis,
 * sql, ...).
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
    public function set(Toggle $toggle)
    {
        $this->toggles[$toggle->getName()] = $toggle;
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
