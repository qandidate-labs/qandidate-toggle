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
 * Operator that compare the given argument on equality based on a value.
 */
abstract class EqualityOperator extends Operator
{
    public function __construct(protected $value)
    {
    }

    /**
     * @return mixed The value compared to
     */
    public function getValue()
    {
        return $this->value;
    }
}
