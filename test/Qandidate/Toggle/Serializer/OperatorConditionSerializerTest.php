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

use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\OperatorCondition;

class OperatorConditionSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function it_serializes_an_operator()
    {
        $serializer = $this->createOperatorConditionSerializer();

        $operator = new OperatorCondition('user_id', new GreaterThan(42));
        $data = $serializer->serialize($operator);

        $this->assertEquals(
            [
                'name' => 'operator-condition',
                'key' => 'user_id',
                'operator' => ['name' => 'greater-than', 'value' => 42],
            ],
            $data
        );
    }

    /**
     * @test
     */
    public function it_deserializes_an_operator()
    {
        $serializer = $this->createOperatorConditionSerializer();

        $serialized = [
            'name' => 'operator-condition',
            'key' => 'user_id',
            'operator' => ['name' => 'greater-than', 'value' => 42],
        ];

        $expected = new OperatorCondition('user_id', new GreaterThan(42));
        $this->assertEquals($expected, $serializer->deserialize($serialized));
    }

    /**
     * @test
     */
    public function it_throws_exception_on_unknown_name()
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createOperatorConditionSerializer();

        $serialized = [
            'name' => 'unknown-name',
            'key' => 'user_id',
            'operator' => ['name' => 'greater-than', 'value' => 42],
        ];

        $serializer->deserialize($serialized);
    }

    /**
     * @test
     * @dataProvider missingKeys
     */
    public function it_throws_exception_on_missing_key($serialized)
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createOperatorConditionSerializer();

        $serializer->deserialize($serialized);
    }

    public function missingKeys()
    {
        return [
            [[]],
            [['name']],
            [['name', 'key']],
            [['name', 'operator']],
        ];
    }

    private function createOperatorConditionSerializer()
    {
        $operatorSerializer = new OperatorSerializer();

        return new OperatorConditionSerializer($operatorSerializer);
    }
}
