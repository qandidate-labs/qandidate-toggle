<?php

declare(strict_types=1);

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Toggle\ToggleCollection;

use Predis\Client;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection;

/**
 * Collection persisted in redis using the Predis client.
 */
class PredisCollection extends ToggleCollection
{
    private $client;
    private $namespace;

    public function __construct($namespace, Client $client)
    {
        $this->namespace = $namespace;
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $keys = $this->client->keys($this->namespace.'__TOGGLE__*');

        $toggles = [];

        foreach ($keys as $key) {
            $toggle = $this->getFromKey($key);

            $toggles[$toggle->getName()] = $toggle;
        }

        return $toggles;
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return $this->getFromKey($this->namespace.'__TOGGLE__'.$name);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, Toggle $toggle)
    {
        $this->client->set($this->namespace.'__TOGGLE__'.$name, serialize($toggle));
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        return 1 === $this->client->del($this->namespace.'__TOGGLE__'.$name);
    }

    private function getFromKey($key)
    {
        $data = $this->client->get($key);

        if (!$data) {
            return null;
        }

        return unserialize($data);
    }
}
