<?php

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
}
