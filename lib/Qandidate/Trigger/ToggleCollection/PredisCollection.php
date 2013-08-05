<?php

namespace Qandidate\Trigger\ToggleCollection;

use Predis\Client;
use Qandidate\Trigger\Toggle;
use Qandidate\Trigger\ToggleCollection;

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
        $this->client    = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function get($name)
    {
        $data = $this->client->get($this->namespace . '__TOGGLE__' . $name);

        if ( ! $data) {
            return null;
        }

        return unserialize($data);
    }

    /**
     * {@inheritDoc}
     */
    public function set($name, Toggle $toggle)
    {
        $this->client->set($this->namespace . '__TOGGLE__' . $name, serialize($toggle));
    }

    /**
     * {@inheritDoc}
     */
    public function remove($name)
    {
        $this->client->del($this->namespace . '__TOGGLE__' . $name);
    }
}
