<?php

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
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function appliesTo($argument)
    {
        return null !== $argument
            && in_array($argument, $this->values);
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
