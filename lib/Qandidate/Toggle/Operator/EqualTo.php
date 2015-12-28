<?php

namespace Qandidate\Toggle\Operator;

class EqualTo extends EqualityOperator
{
    /**
     * {@inheritdoc}
     */
    public function appliesTo($argument)
    {
        return $argument === $this->value;
    }
}
