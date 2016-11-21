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

class InSet extends Operator
{
    private $values;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = array_map('trim',$values);
    }

    /**
     * {@inheritdoc}
     */
    public function appliesTo($argument)
    {
        return null !== $argument
        && (in_array($argument, $this->values, true) || (isset($this->values[$argument]) && $this->values[$argument]));
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
