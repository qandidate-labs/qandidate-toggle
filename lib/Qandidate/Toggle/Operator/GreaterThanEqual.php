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

class GreaterThanEqual extends EqualityOperator
{
    /**
     * {@inheritdoc}
     */
    public function appliesTo($argument)
    {
        return $argument >= $this->value;
    }
}
