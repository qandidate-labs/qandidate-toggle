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

use InvalidArgumentException;


/**
 * @template T
 * @template-extends EqualityOperator<T>
 */
class HasIntersection extends EqualityOperator
{
    /**
     * @param array<T> $values
     */
    public function __construct(private readonly array $values)
    {
    }

    public function appliesTo(mixed $argument): bool
    {
        if (!is_array($argument)) {
            throw new InvalidArgumentException('HasIntersection can only be compared against array values');
        }

        return null !== $argument
            && array_intersect($argument, $this->values);
    }

    public function getValues(): array
    {
        return $this->values;
    }
}
