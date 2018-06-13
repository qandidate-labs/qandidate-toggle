<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\Operator;
use Qandidate\Toggle\Operator\EqualTo;
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\Operator\GreaterThanEqual;
use Qandidate\Toggle\Operator\HasIntersection;
use Qandidate\Toggle\Operator\InSet;
use Qandidate\Toggle\Operator\NotInSet;
use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\Operator\LessThanEqual;
use Qandidate\Toggle\Operator\Percentage;
use Qandidate\Toggle\Operator\MatchesRegex;
use RuntimeException;

/**
 * Hand written serializer to serialize an Operator to a php array.
 */
class OperatorSerializer
{
    /**
     * @param Operator $operator
     *
     * @return string
     */
    public function serialize(Operator $operator)
    {
        switch(get_class($operator)) {
            case EqualTo::class:
                return array('name' => 'equal-to', 'value' => $operator->getValue());
            case GreaterThan::class:
                return array('name' => 'greater-than', 'value' => $operator->getValue());
            case GreaterThanEqual::class:
                return array('name' => 'greater-than-equal', 'value' => $operator->getValue());
            case HasIntersection::class:
                return array('name' => 'has-intersection', 'values' => $operator->getValues());
            case InSet::class:
                return array('name' => 'in-set', 'values' => $operator->getValues());
            case NotInSet::class:
                return array('name' => 'not-in-set', 'values' => $operator->getValues());
            case LessThan::class:
                return array('name' => 'less-than', 'value' => $operator->getValue());
            case LessThanEqual::class:
                return array('name' => 'less-than-equal', 'value' => $operator->getValue());
            case Percentage::class:
                return array('name' => 'percentage', 'percentage' => $operator->getPercentage(), 'shift' => $operator->getShift());
            case MatchesRegex::class:
                return array('name' => 'matches-regex', 'value' => $operator->getValue());
            default:
                throw new RuntimeException(sprintf('Unknown operator %s.', get_class($operator)));
        }
    }

    /**
     * @param array $operator
     *
     * @return Operator
     */
    public function deserialize(array $operator)
    {
        $this->assertHasKey('name', $operator);

        switch($operator['name']) {
            case 'equals-to': // Left for backward compatibility, todo: should be removed in future
            case 'equal-to':
                $this->assertHasKey('value', $operator);

                return new EqualTo($operator['value']);
            case 'greater-than':
                $this->assertHasKey('value', $operator);

                return new GreaterThan($operator['value']);
            case 'greater-than-equal':
                $this->assertHasKey('value', $operator);

                return new GreaterThanEqual($operator['value']);
            case 'has-intersection':
                $this->assertHasKey('values', $operator);

                return new HasIntersection($operator['values']);
            case 'in-set':
                $this->assertHasKey('values', $operator);

                return new InSet($operator['values']);
            case 'not-in-set':
                $this->assertHasKey('values', $operator);

                return new NotInSet($operator['values']);
            case 'less-than':
                $this->assertHasKey('value', $operator);

                return new LessThan($operator['value']);
            case 'less-than-equal':
                $this->assertHasKey('value', $operator);

                return new LessThanEqual($operator['value']);
            case 'percentage':
                $this->assertHasKey('percentage', $operator);
                $this->assertHasKey('shift', $operator);

                return new Percentage($operator['percentage'], $operator['shift']);
            case 'matches-regex':
                $this->assertHasKey('value', $operator);

                return new MatchesRegex($operator['value']);
            default:
                throw new RuntimeException(sprintf('Unknown operator with name "%s".', $operator['name']));
        }
    }

    private function assertHasKey($key, array $data)
    {
        if ( ! array_key_exists($key, $data)) {
            throw new RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
}
