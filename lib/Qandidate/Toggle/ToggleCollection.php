<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
