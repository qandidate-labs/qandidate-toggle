<?php

namespace Qandidate\Toggle\ToggleCollection;

use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollectionTest;

class InMemoryCollectionTest extends ToggleCollectionTest
{
    protected function createCollection()
    {
        return new InMemoryCollection();
    }
}
