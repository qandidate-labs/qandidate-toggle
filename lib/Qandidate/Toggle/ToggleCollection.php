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
 * A collection of toggles, used by a manager.
 *
 * Abstraction to allow for different storage backends of toggles (e.g. redis,
 * sql, ...).
 */
interface ToggleCollection
{
    /**
     * @return Toggle[]
     */
    public function all(): array;

    public function get(string $name): ?Toggle;

    public function set(string $name, Toggle $toggle): void;

    public function remove(string $name): void;
}
