<?php

namespace Qandidate\Trigger\ToggleCollection;

use Qandidate\Trigger\Toggle;
use Qandidate\Trigger\ToggleCollectionTest;

class InMemoryCollectionTest extends ToggleCollectionTest
{
    protected function createCollection()
    {
        return new InMemoryCollection();
    }
}
