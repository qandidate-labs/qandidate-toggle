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