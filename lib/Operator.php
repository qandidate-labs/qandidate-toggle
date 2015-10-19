<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Toggle;

/**
 * Operator calculates whether it applies to an argument or not.
 */
abstract class Operator
{
    /**
     * @param mixed $argument
     *
     * @return boolean True, if the operator applies to the argument
     */
    abstract public function appliesTo($argument);
}
