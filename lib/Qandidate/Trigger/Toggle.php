<?php

namespace Qandidate\Trigger;

/**
 * Representation of a feature toggle.
 *
 * Encapsulates the conditions that should hold for a context in order for the 
 * toggle to be active.
 *
 * @todo Rename to Switch when possible in PHP
 */
class Toggle
{
    private $name;
    private $conditions;

    public function __construct($name, array $conditions)
    {
        $this->name       = $name;
        $this->conditions = $conditions;
    }

    /**
     * Checks whether the toggle is active for the given context.
     *
     * @param Context $context 
     *
     * @return booleaan True, if one of conditions hold for the context.
     */
    public function activeFor(Context $context)
    {
        foreach ($this->conditions as $condition) {
            if ($condition->holdsFor($context)) {
                return true;
            }
        }

        return false;
    }
}
