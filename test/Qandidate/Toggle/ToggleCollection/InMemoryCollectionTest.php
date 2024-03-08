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

use Qandidate\Toggle\ToggleCollectionTest;

class InMemoryCollectionTest extends ToggleCollectionTest
{
    protected function createCollection(): InMemoryCollection
    {
        return new InMemoryCollection();
    }
}
