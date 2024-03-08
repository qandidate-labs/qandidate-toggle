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
 * Operator calculates whether it applies to an argument or not.
 *
 * @template T
 */
interface Operator
{
    /**
     * @param T $argument
     *
     * @return bool returns true if the operator applies to the argument
     */
    public function appliesTo(mixed $argument): bool;
}
