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

class OperatorSerializerTest extends TestCase
{
    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unknown operator Qandidate\Toggle\Serializer\UnknownOperator.
     */
    public function it_throws_exception_on_unknown_operator()
    {
        $serializer = new OperatorSerializer();

        $serializer->serialize(new UnknownOperator());
    }

    /**
     * @test
     * @dataProvider knownOperators
     */
    public function it_serializes_known_operators($operator, $expected)
    {
        $serializer = new OperatorSerializer();

        $data = $serializer->serialize($operator);
        $this->assertEquals($expected, $data);
    }

    public function knownOperators()
    {
        return array(
            array(new GreaterThan(42), array('name' => 'greater-than', 'value' => 42)),
            array(new GreaterThanEqual(42), array('name' => 'greater-than-equal', 'value' => 42)),
            array(new LessThan(42), array('name' => 'less-than', 'value' => 42)),
            array(new LessThanEqual(42), array('name' => 'less-than-equal', 'value' => 42)),
            array(new Percentage(42, 5), array('name' => 'percentage', 'percentage' => 42, 'shift' => 5)),
            array(new InSet(array(1, 2, 3)), array('name' => 'in-set', 'values' => array(1, 2, 3))),
        );
    }

    /**
     * @test
     * @dataProvider knownOperators
     */
    public function it_deserializes_known_operators($expected, $serialized)
    {
        $serializer = new OperatorSerializer();

        $operator = $serializer->deserialize($serialized);
        $this->assertEquals($expected, $operator);
    }

    /**
     * @test
     * @dataProvider missingKeys
     * @expectedException RuntimeException
     */
    public function it_throws_an_exception_if_a_key_is_missing_from_the_data($serialized)
    {
        $serializer = new OperatorSerializer();

        $operator = $serializer->deserialize($serialized);
    }

    public function missingKeys()
    {
        return array(
            array(array()),
            array(array('name' => 'greater-than')),
            array(array('name' => 'greater-than-equal')),
            array(array('name' => 'less-than')),
            array(array('name' => 'less-than-equal')),
            array(array('name' => 'percentage')),
            array(array('name' => 'percentage', 'percentage' => 42)),
            array(array('name' => 'percentage', 'shift' => 5)),
            array(array('name' => 'in-set')),
        );
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_throws_an_exception_on_deserializing_unknown_operator()
    {
        $serializer = new OperatorSerializer();

        $operator = $serializer->deserialize(array('name' => 'unknown'));
    }
}

class UnknownOperator extends Operator
{
    public function appliesTo($argument)
    {
        return true;
    }
}
