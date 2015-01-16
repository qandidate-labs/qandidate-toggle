<?php

namespace Qandidate\Toggle\Operator;

class MatchesRegex extends EqualityOperator
{
    /**
     * {@inheritDoc}
     */
    public function appliesTo($argument)
    {
        return (bool) preg_match($this->value, $argument);
    }
} 