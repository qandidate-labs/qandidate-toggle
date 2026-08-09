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

namespace Qandidate\Toggle\Serializer;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\Operator;
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\Operator\GreaterThanEqual;
use Qandidate\Toggle\Operator\HasIntersection;
use Qandidate\Toggle\Operator\InSet;
use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\Operator\LessThanEqual;
use Qandidate\Toggle\Operator\NotInSet;
use Qandidate\Toggle\Operator\Percentage;

class OperatorSerializerTest extends TestCase
{
    #[Test]
    public function it_throws_exception_on_unknown_operator()
    {
        $this->expectException('RuntimeException');
        $this->expectExceptionMessage('Unknown operator Qandidate\Toggle\Serializer\UnknownOperator.');
        $serializer = new OperatorSerializer();

        $serializer->serialize(new UnknownOperator());
    }

    #[Test]
    #[DataProvider('knownOperators')]
    public function it_serializes_known_operators($operator, $expected)
    {
        $serializer = new OperatorSerializer();

        $data = $serializer->serialize($operator);
        $this->assertEquals($expected, $data);
    }

    public static function knownOperators()
    {
        return [
            [new GreaterThan(42), ['name' => 'greater-than', 'value' => 42]],
            [new GreaterThanEqual(42), ['name' => 'greater-than-equal', 'value' => 42]],
            [new LessThan(42), ['name' => 'less-than', 'value' => 42]],
            [new LessThanEqual(42), ['name' => 'less-than-equal', 'value' => 42]],
            [new Percentage(42, 5), ['name' => 'percentage', 'percentage' => 42, 'shift' => 5]],
            [new HasIntersection([1, 2, 3]), ['name' => 'has-intersection', 'values' => [1, 2, 3]]],
            [new InSet([1, 2, 3]), ['name' => 'in-set', 'values' => [1, 2, 3]]],
            [new NotInSet([1, 2, 3]), ['name' => 'not-in-set', 'values' => [1, 2, 3]]],
        ];
    }

    #[Test]
    #[DataProvider('knownOperators')]
    public function it_deserializes_known_operators($expected, $serialized)
    {
        $serializer = new OperatorSerializer();

        $operator = $serializer->deserialize($serialized);
        $this->assertEquals($expected, $operator);
    }

    #[Test]
    #[DataProvider('missingKeys')]
    public function it_throws_an_exception_if_a_key_is_missing_from_the_data($serialized)
    {
        $this->expectException('RuntimeException');
        $serializer = new OperatorSerializer();

        $operator = $serializer->deserialize($serialized);
    }

    public static function missingKeys()
    {
        return [
            [[]],
            [['name' => 'greater-than']],
            [['name' => 'greater-than-equal']],
            [['name' => 'less-than']],
            [['name' => 'less-than-equal']],
            [['name' => 'percentage']],
            [['name' => 'percentage', 'percentage' => 42]],
            [['name' => 'percentage', 'shift' => 5]],
            [['name' => 'in-set']],
            [['name' => 'not-in-set']],
            [['name' => 'has-intersection']],
        ];
    }

    #[Test]
    public function it_throws_an_exception_on_deserializing_unknown_operator()
    {
        $this->expectException('RuntimeException');
        $serializer = new OperatorSerializer();

        $operator = $serializer->deserialize(['name' => 'unknown']);
    }
}

class UnknownOperator extends Operator
{
    public function appliesTo($argument): bool
    {
        return true;
    }
}
