<?php

namespace Qandidate\Toggle\Operator;

use Qandidate\Toggle\Operator;

class Percentage extends Operator
{
    private $percentage;
    private $shift;

    public function __construct($percentage, $shift = 0)
    {
        $this->percentage = $percentage;
        $this->shift      = $shift;
    }

    /**
     * {@inheritdoc}
     */
    public function appliesTo($argument)
    {
        $asPercentage = $argument % 100;

        return $asPercentage >= $this->shift &&
            $asPercentage < ($this->percentage + $this->shift);
    }
}
