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
 * The context object represents the current state of the application that is
 * important to decide whether a feature is active or not.
 */
class Context
{
    /** @var array */
    private $values = array();

    /**
     * @param int|string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->values[$key];
    }

    /**
     * @param int|string $key
     * @param mixed      $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * @param int|string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * @return array|mixed[]
     */
    public function toArray()
    {
        return $this->values;
    }
}
