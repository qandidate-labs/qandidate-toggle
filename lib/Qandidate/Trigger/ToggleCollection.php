<?php

namespace Qandidate\Trigger;

/**
 * A collection of toggles, used by a manager.
 *
 * Abstraction to allow for different storage backends of toggles (e.g. redis,
 * sql, ...).
 */
abstract class ToggleCollection
{
    /**
     * @param string $name
     *
     * @return null|Toggle
     */
    abstract public function get($name);

    /**
     * @param Toggle $toggle
     */
    abstract public function set(Toggle $toggle);

    /**
     * @param string $name
     */
    abstract public function remove($name);
}
