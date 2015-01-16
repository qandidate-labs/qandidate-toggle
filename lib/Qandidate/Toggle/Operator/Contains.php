<?php

namespace Qandidate\Toggle\Operator;

class Contains extends EqualityOperator
{
    /**
     * {@inheritDoc}
     */
    public function appliesTo($argument)
    {
        return false !== strpos($argument, $this->value);
    }
} 