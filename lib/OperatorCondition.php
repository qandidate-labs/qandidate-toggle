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
 * A condition based on the name of the value from the context and an operator.
 */
class OperatorCondition extends Condition
{
    private $key;
    private $operator;

    /**
     * @param string   $key      Name of the value
     * @param Operator $operator Operator to run
     */
    public function __construct($key, Operator $operator)
    {
        $this->key      = $key;
        $this->operator = $operator;
    }
    /**
     * {@inheritdoc}
     */
    public function holdsFor(Context $context)
    {
        if ( ! $context->has($this->key)) {
            return false;
        }

        $argument = $context->get($this->key);

        return $this->operator->appliesTo($argument);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return Operator
     */
    public function getOperator()
    {
        return $this->operator;
    }
}
