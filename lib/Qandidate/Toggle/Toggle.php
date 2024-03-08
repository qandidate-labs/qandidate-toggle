<?php

declare(strict_types=1);

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
 * Representation of a feature toggle.
 *
 * Encapsulates the conditions that should hold for a context in order for the
 * toggle to be active.
 *
 * @todo Rename to Switch when possible in PHP
 */
class Toggle
{
    public const CONDITIONALLY_ACTIVE = 1;
    public const ALWAYS_ACTIVE = 2;
    public const INACTIVE = 4;

    /** At least one of the provided conditions has to be met */
    public const STRATEGY_AFFIRMATIVE = 1;

    /** At least half of the provided conditions have to be met */
    public const STRATEGY_MAJORITY = 2;

    /** All conditions have to be met */
    public const STRATEGY_UNANIMOUS = 3;

    private int $status = self::CONDITIONALLY_ACTIVE;

    private readonly int $strategy;

    /**
     * @param Condition[] $conditions
     */
    public function __construct(private string $name, private readonly array $conditions, int $strategy = self::STRATEGY_AFFIRMATIVE)
    {
        $this->assertValidStrategy($strategy);
        $this->strategy = $strategy;
    }

    public function activate(int $status = self::CONDITIONALLY_ACTIVE): void
    {
        $this->assertValidActiveStatus($status);
        $this->status = $status;
    }

    /**
     * Checks whether the toggle is active for the given context.
     *
     * @return bool true, if one of conditions hold for the context
     */
    public function activeFor(Context $context): bool
    {
        return match ($this->status) {
            self::ALWAYS_ACTIVE => true,
            self::INACTIVE => false,
            self::CONDITIONALLY_ACTIVE => $this->executeCondition($context),
            default => false,
        };
    }

    /**
     * Immediately set this toggle's status to inactive.
     */
    public function deactivate(): void
    {
        $this->status = self::INACTIVE;
    }

    /**
     * @return Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function rename(string $newName): void
    {
        $this->name = $newName;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getStrategy(): int
    {
        return $this->strategy;
    }

    private function assertValidActiveStatus(int $status): void
    {
        if (self::ALWAYS_ACTIVE !== $status && self::CONDITIONALLY_ACTIVE !== $status) {
            throw new \InvalidArgumentException('No "active" status was provided.');
        }
    }

    private function assertValidStrategy(int $strategy): void
    {
        if (!in_array($strategy, [
            self::STRATEGY_AFFIRMATIVE,
            self::STRATEGY_MAJORITY,
            self::STRATEGY_UNANIMOUS,
        ])) {
            throw new \InvalidArgumentException('No supported strategy was provided.');
        }
    }

    private function executeCondition(Context $context): bool
    {
        return match ($this->strategy) {
            self::STRATEGY_AFFIRMATIVE => $this->atLeastOneConditionHolds($context),
            self::STRATEGY_MAJORITY => $this->moreThanHalfConditionsHold($context),
            self::STRATEGY_UNANIMOUS => $this->allConditionsHold($context),
            default => false,
        };
    }

    private function atLeastOneConditionHolds(Context $context): bool
    {
        foreach ($this->conditions as $condition) {
            if ($condition->holdsFor($context)) {
                return true;
            }
        }

        return false;
    }

    private function moreThanHalfConditionsHold(Context $context): bool
    {
        $nbPositive = 0;
        $nbNegative = 0;

        foreach ($this->conditions as $condition) {
            $condition->holdsFor($context) ? $nbPositive++ : $nbNegative++;
        }

        return $nbPositive > $nbNegative;
    }

    private function allConditionsHold(Context $context): bool
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->holdsFor($context)) {
                return false;
            }
        }

        return true;
    }
}
