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
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\Operator\GreaterThanEqual;
use Qandidate\Toggle\Operator\InSet;
use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\Operator\LessThanEqual;
use Qandidate\Toggle\Operator\Percentage;
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
        switch(true) {
            case $operator instanceof GreaterThan:
                return array('name' => 'greater-than', 'value' => $operator->getValue());
            case $operator instanceof GreaterThanEqual:
                return array('name' => 'greater-than-equal', 'value' => $operator->getValue());
            case $operator instanceof InSet:
                return array('name' => 'in-set', 'values' => $operator->getValues());
            case $operator instanceof LessThan:
                return array('name' => 'less-than', 'value' => $operator->getValue());
            case $operator instanceof LessThanEqual:
                return array('name' => 'less-than-equal', 'value' => $operator->getValue());
            case $operator instanceof Percentage:
                return array('name' => 'percentage', 'percentage' => $operator->getPercentage(), 'shift' => $operator->getShift());
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
            case 'greater-than':
                $this->assertHasKey('value', $operator);

                return new GreaterThan($operator['value']);
            case 'greater-than-equal':
                $this->assertHasKey('value', $operator);

                return new GreaterThanEqual($operator['value']);
            case 'in-set':
                $this->assertHasKey('values', $operator);

                return new InSet($operator['values']);
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
