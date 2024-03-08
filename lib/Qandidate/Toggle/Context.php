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

namespace Qandidate\Toggle;

/**
 * The context object represents the current state of the application that is
 * important to decide whether a feature is active or not.
 */
class Context
{
    private array $values = [];

    public function get(int|string $key): mixed
    {
        return $this->values[$key];
    }

    public function set(int|string $key, mixed $value): static
    {
        $this->values[$key] = $value;

        return $this;
    }

    public function has(int|string $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    public function toArray(): array
    {
        return $this->values;
    }
}
