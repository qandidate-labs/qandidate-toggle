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

    /** At least one of the provided conditions has to be met */
    const STRATEGY_AFFIRMATIVE = 1;

    /** At least half of the provided conditions have to be met */
    const STRATEGY_MAJORITY    = 2;

    /** All conditions have to be met */
    const STRATEGY_UNANIMOUS   = 3;

    /** @var string */
    private $name;

    /** @var array|Condition[] */
    private $conditions;

    /** @var int */
    private $status = self::CONDITIONALLY_ACTIVE;

    /** @var int */
    private $strategy = self::STRATEGY_AFFIRMATIVE;

    /**
     * @param                   $name
     * @param array|Condition[] $conditions
     * @param int               $strategy
     */
    public function __construct($name, array $conditions, $strategy = self::STRATEGY_AFFIRMATIVE)
    {
        $this->name       = $name;
        $this->conditions = $conditions;
        $this->assertValidStrategy($strategy);
        $this->strategy   = $strategy;
    }

    /**
     * @param int $status
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
     * @return bool True, if one of conditions hold for the context.
     */
    public function activeFor(Context $context)
    {
        switch ($this->status) {
            case self::ALWAYS_ACTIVE:
                return true;
            case self::INACTIVE:
                return false;
            case self::CONDITIONALLY_ACTIVE:
                switch ($this->strategy) {
                    case self::STRATEGY_AFFIRMATIVE:
                        return $this->atLeastOneConditionHolds($context);
                    case self::STRATEGY_MAJORITY:
                        return $this->moreThanHalfConditionsHold($context);
                    case self::STRATEGY_UNANIMOUS:
                        return $this->allConditionsHold($context);
                }
        }
    }

    /**
     * Immediately set this toggle's status to inactive
     *
     * @return int The status code after deactivation
     */
    public function deactivate()
    {
        return $this->status = self::INACTIVE;
    }

    /**
     * @return array|Condition[]
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
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param int $status
     */
    private function assertValidActiveStatus($status)
    {
        if ($status !== self::ALWAYS_ACTIVE && $status !== self::CONDITIONALLY_ACTIVE) {
            throw new InvalidArgumentException('No "active" status was provided.');
        }
    }

    /**
     * @param int $strategy
     */
    private function assertValidStrategy($strategy)
    {
        if (! in_array($strategy, array(
            self::STRATEGY_AFFIRMATIVE,
            self::STRATEGY_MAJORITY,
            self::STRATEGY_UNANIMOUS
        ))) {
            throw new InvalidArgumentException('No supported strategy was provided.');
        }
    }

    /**
     * @param Context $context
     *
     * @return bool
     */
    private function atLeastOneConditionHolds(Context $context)
    {
        foreach ($this->conditions as $condition) {
            if ($condition->holdsFor($context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Context $context
     *
     * @return bool
     */
    private function moreThanHalfConditionsHold(Context $context)
    {
        $nbPositive = 0;
        $nbNegative = 0;

        foreach ($this->conditions as $condition) {
            $condition->holdsFor($context) ? $nbPositive++ : $nbNegative++;
        }

        return $nbPositive > $nbNegative;
    }

    /**
     * @param Context $context
     *
     * @return bool
     */
    private function allConditionsHold(Context $context)
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->holdsFor($context)) {
                return false;
            }
        }

        return true;
    }
}
