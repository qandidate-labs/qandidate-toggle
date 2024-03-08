<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\TestCase;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;

class InMemoryCollectionSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function it_unserializes_a_collection_from_an_array(): void
    {
        $data = [
            [
                'name' => 'toggling',
                'conditions' => [
                    [
                        'name' => 'operator-condition',
                        'key' => 'user_id',
                        'operator' => ['name' => 'greater-than', 'value' => 42],
                    ],
                ],
                'status' => 'conditionally-active',
            ],
        ];

        $serializer = new InMemoryCollectionSerializer();
        $collection = $serializer->deserialize($data);

        $this->assertInstanceOf(Toggle::class, $collection->get('toggling'));
        $this->assertCount(1, $collection->all());
    }

    /**
     * @test
     */
    public function it_serializes_a_collection_to_an_array(): void
    {
        $collection = new InMemoryCollection();
        $operator = new LessThan(42);
        $condition = new OperatorCondition('user_id', $operator);
        $toggle = new Toggle('toggling', [$condition]);
        $collection->set('some-feature', $toggle);
        $serializer = new InMemoryCollectionSerializer();

        $serialized = $serializer->serialize($collection);

        $this->assertIsArray($serialized);
        $this->assertCount(1, $serialized);
        $this->assertArrayHasKey('name', $serialized[0]);
        $this->assertArrayHasKey('conditions', $serialized[0]);
        $this->assertSame('toggling', $serialized[0]['name']);
    }

    /**
     * @test
     */
    public function it_serializes_and_deserializes_a_collection(): void
    {
        $collection = new InMemoryCollection();
        $operator = new LessThan(42);
        $condition = new OperatorCondition('user_id', $operator);
        $toggle = new Toggle('toggling', [$condition]);
        $collection->set('toggling', $toggle);
        $serializer = new InMemoryCollectionSerializer();

        $serialized = $serializer->serialize($collection);
        $collection2 = $serializer->deserialize($serialized);

        $this->assertEquals($collection, $collection2);
    }
}
