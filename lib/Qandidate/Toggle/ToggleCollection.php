<?php

namespace Qandidate\Toggle;

/**
 * A collection of toggles, used by a manager.
 *
 * Abstraction to allow for different storage backends of toggles (e.g. redis,
 * sql, ...).
 */
abstract class ToggleCollection
{
    /**
     * @return array
     */
    abstract public function all();

    /**
     * @param string $name
     *
     * @return null|Toggle
     */
    abstract public function get($name);

    /**
     * @param string $name
     * @param Toggle $toggle
     */
    abstract public function set($name, Toggle $toggle);

    /**
     * @param string $name
     *
     * @return boolean True, if element was removed
     */
    abstract public function remove($name);
}
