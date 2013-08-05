<?php

namespace Qandidate\Toggle\ToggleCollection;

use Predis\Client;
use Qandidate\Toggle\ToggleCollectionTest;

class PredisCollectionTest extends ToggleCollectionTest
{
    private $client;
    private $collection;

    public function setUp()
    {
        $this->client     = new Client();
        $this->collection = new PredisCollection('toggle_predis_test', $this->client);
    }

    public function tearDown()
    {
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
