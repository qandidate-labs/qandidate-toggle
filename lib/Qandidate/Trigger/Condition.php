<?php

namespace Qandidate\Trigger;

/**
 * For a condition it can be checked if it holds for a given context.
 */
abstract class Condition
{
    /**
     * @param Context $context
     *
     * @return boolean True, if the condition holds for the given context
     */
    abstract public function holdsFor(Context $context);
}
