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

/**
 * @template T
 * @template-extends EqualityOperator<T> 
 */
class LessThan extends EqualityOperator
{
    public function appliesTo(mixed $argument): bool
    {
        return $argument < $this->value;
    }
}
