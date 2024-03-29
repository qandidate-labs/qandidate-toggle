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

use Predis\ClientInterface;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection;

/**
 * Collection persisted in redis using the Predis client.
 */
class PredisCollection extends ToggleCollection
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $namespace;

    public function __construct(string $namespace, ClientInterface $client)
    {
        $this->namespace = $namespace;
        $this->client = $client;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function all(): array
    {
        $keys = $this->client->keys($this->namespace.'__TOGGLE__*');

        $toggles = [];

        foreach ($keys as $key) {
            $toggle = $this->getFromKey($key);

            $toggles[$toggle->getName()] = $toggle;
        }

        return $toggles;
    }

    public function get(string $name): ?Toggle
    {
        return $this->getFromKey($this->namespace.'__TOGGLE__'.$name);
    }

    public function set(string $name, Toggle $toggle): void
    {
        $this->client->set($this->namespace.'__TOGGLE__'.$name, serialize($toggle));
    }

    public function remove(string $name): void
    {
        $this->client->del([$this->namespace.'__TOGGLE__'.$name]);
    }

    /**
     * @return mixed|null
     */
    private function getFromKey(string $key)
    {
        $data = $this->client->get($key);

        if (!$data) {
            return null;
        }

        return unserialize($data);
    }
}
