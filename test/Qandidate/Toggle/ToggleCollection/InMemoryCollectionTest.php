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

use Qandidate\Toggle\ToggleCollectionTestCase;

class InMemoryCollectionTest extends ToggleCollectionTestCase
{
    protected function createCollection()
    {
        return new InMemoryCollection();
    }
}
