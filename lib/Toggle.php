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

use InvalidArgumentException;

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
    const CONDITIONALLY_ACTIVE = 1;
    const ALWAYS_ACTIVE        = 2;
    const INACTIVE             = 4;

    private $name;
    private $conditions;
    private $status = self::CONDITIONALLY_ACTIVE;

    public function __construct($name, array $conditions)
    {
        $this->name       = $name;
        $this->conditions = $conditions;
    }

    /**
     * @param integer $status
     */
    public function activate($status = self::CONDITIONALLY_ACTIVE)
    {
        $this->assertValidActiveStatus($status);
        $this->status = $status;
    }

    /**
     * Checks whether the toggle is active for the given context.
     *
     * @param Context $context
     *
     * @return boolean True, if one of conditions hold for the context.
     */
    public function activeFor(Context $context)
    {
        switch ($this->status) {
            case self::ALWAYS_ACTIVE:
                return true;
            case self::INACTIVE:
                return false;
            case self::CONDITIONALLY_ACTIVE:
                return $this->atLeastOneConditionHolds($context);
        }
    }

    public function deactivate()
    {
        return $this->status = self::INACTIVE;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $newName
     */
    public function rename($newName)
    {
        $this->name = $newName;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    private function assertValidActiveStatus($status)
    {
        if ($status !== self::ALWAYS_ACTIVE && $status !== self::CONDITIONALLY_ACTIVE) {
            throw new InvalidArgumentException('No "active" status was provided.');
        }
    }

    private function atLeastOneConditionHolds(Context $context)
    {
        foreach ($this->conditions as $condition) {
            if ($condition->holdsFor($context)) {
                return true;
            }
        }

        return false;
    }
}
