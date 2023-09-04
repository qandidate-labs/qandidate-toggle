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

use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection;

/**
 * In memory collection useful for testing or when toggles are loaded
 * "statically" from for example configuration.
 */
class InMemoryCollection extends ToggleCollection
{
    /**
     * @var Toggle[]
     */
    private $toggles = [];

    public function all(): array
    {
        return $this->toggles;
    }

    public function get(string $name): ?Toggle
    {
        if (!array_key_exists($name, $this->toggles)) {
            return null;
        }

        return $this->toggles[$name];
    }

    public function set(string $name, Toggle $toggle): void
    {
        $this->toggles[$name] = $toggle;
    }

    public function remove(string $name): void
    {
        if (array_key_exists($name, $this->toggles)) {
            unset($this->toggles[$name]);
        }
    }
}
