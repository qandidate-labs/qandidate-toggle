<?php

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
