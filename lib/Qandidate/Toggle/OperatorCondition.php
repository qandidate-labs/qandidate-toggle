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
 * A condition based on the name of the value from the context and an operator.
 */
class OperatorCondition extends Condition
{
    /** @var string */
    private $key;

    /** @var Operator */
    private $operator;

    /**
     * @param string   $key      Name of the value
     * @param Operator $operator Operator to run
     */
    public function __construct(string $key, Operator $operator)
    {
        $this->key = $key;
        $this->operator = $operator;
    }

    public function holdsFor(Context $context): bool
    {
        if (!$context->has($this->key)) {
            return false;
        }

        $argument = $context->get($this->key);

        return $this->operator->appliesTo($argument);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getOperator(): Operator
    {
        return $this->operator;
    }
}
