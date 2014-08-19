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

use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Operator\GreaterThan;

class OperatorConditionSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function it_serializes_an_operator()
    {
        $serializer = $this->createOperatorConditionSerializer();

        $operator   = new OperatorCondition('user_id', new GreaterThan(42));
        $data = $serializer->serialize($operator);

        $this->assertEquals(
            array(
                'name' => 'operator-condition',
                'key' => 'user_id',
                'operator' => array('name' => 'greater-than', 'value' => 42)
            ),
            $data
        );
    }

    /**
     * @test
     */
    public function it_deserializes_an_operator()
    {
        $serializer = $this->createOperatorConditionSerializer();

        $serialized = array(
            'name' => 'operator-condition',
            'key' => 'user_id',
            'operator' => array('name' => 'greater-than', 'value' => 42)
        );

        $expected = new OperatorCondition('user_id', new GreaterThan(42));
        $this->assertEquals($expected, $serializer->deserialize($serialized));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_throws_exception_on_unknown_name()
    {
        $serializer = $this->createOperatorConditionSerializer();

        $serialized = array(
            'name' => 'unknown-name',
            'key' => 'user_id',
            'operator' => array('name' => 'greater-than', 'value' => 42)
        );

        $serializer->deserialize($serialized);
    }

    /**
     * @test
     * @dataProvider missingKeys
     * @expectedException RuntimeException
     */
    public function it_throws_exception_on_missing_key($serialized)
    {
        $serializer = $this->createOperatorConditionSerializer();

        $serializer->deserialize($serialized);
    }

    public function missingKeys()
    {
        return array(
            array(array()),
            array(array('name')),
            array(array('name', 'key')),
            array(array('name', 'operator')),
        );
    }

    private function createOperatorConditionSerializer()
    {
        $operatorSerializer = new OperatorSerializer();

        return new OperatorConditionSerializer($operatorSerializer);
    }
}
