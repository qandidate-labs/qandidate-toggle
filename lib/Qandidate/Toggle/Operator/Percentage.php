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

    public function getPercentage()
    {
        return $this->percentage;
    }

    public function getShift()
    {
        return $this->shift;
    }
}
