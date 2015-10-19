<?php

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
    /**
     * @return mixed The value compared to
     */
    abstract public function getValue();
}
