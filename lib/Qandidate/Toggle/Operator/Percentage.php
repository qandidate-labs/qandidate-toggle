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

namespace Qandidate\Toggle\Operator;

use Qandidate\Toggle\Operator;

class Percentage implements Operator
{
    public function __construct(private readonly int $percentage, private readonly int $shift = 0)
    {
    }

    public function appliesTo(mixed $argument): bool
    {
        if (!is_int($argument)) {
            throw new \InvalidArgumentException('Percentage only accepts integers');
        }

        $asPercentage = (int) $argument % 100;

        return $asPercentage >= $this->shift
            && $asPercentage < ($this->percentage + $this->shift);
    }

    public function getPercentage(): int
    {
        return $this->percentage;
    }

    public function getShift(): int
    {
        return $this->shift;
    }
}
