<?php

namespace Qandidate\Toggle\Operator;

class EqualsTo extends EqualityOperator
{
    /**
     * {@inheritdoc}
     */
    public function appliesTo($argument)
    {
        return $argument === $this->value;
    }
}