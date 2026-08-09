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
use Predis\PredisException;
use Qandidate\Toggle\ToggleCollectionTestCase;

class PredisCollectionTest extends ToggleCollectionTestCase
{
    private $client;
    private $collection;

    public function setUp(): void
    {
        $this->client = new Client();
        $this->collection = new PredisCollection('toggle_predis_test', $this->client);
        try {
            $this->client->connect();
        } catch (PredisException $e) {
            $this->markTestSkipped('Failed to connect to redis.');
        }
    }

    public function tearDown(): void
    {
        if (!$this->client->isConnected()) {
            return;
        }

        $keys = $this->client->keys('toggle_predis_test*');

        foreach ($keys as $key) {
            $this->client->del($key);
        }
    }

    public function createCollection()
    {
        return $this->collection;
    }
}
