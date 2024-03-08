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

/**
 * @template T
 */
class InSet implements Operator
{
    /**
     * @param array<T> $values
     */
    public function __construct(private readonly array $values)
    {
    }

    /**
     * @param T $argument
     */
    public function appliesTo(mixed $argument): bool
    {
        return null !== $argument
            && in_array($argument, $this->values);
    }

    /**
     * @return array<T>
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
