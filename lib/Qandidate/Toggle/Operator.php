<?php

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
