<?php

namespace Qandidate\Toggle\ToggleCollection;

use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;
use Qandidate\Toggle\TestCase;
use Qandidate\Toggle\Toggle;

class InMemoryCollectionSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function it_unserializes_a_collection_from_an_array()
    {
        $data = array(
            'some-feature' => array(
                'name' => 'toggling',
                'conditions' => array(
                    array(
                        'name' => 'operator-condition',
                        'key' => 'user_id',
                        'operator' => array('name' => 'greater-than', 'value' => 42),
                    ),
                ),
                'status' => 'conditionally-active',
            ),
        );

        $serializer = new InMemoryCollectionSerializer();
        $collection = $serializer->deserialize($data);

        $this->assertInstanceOf('Qandidate\Toggle\Toggle', $collection->get('some-feature'));
        $this->assertCount(1, $collection->all());
    }

    /**
     * @test
     */
    public function it_serializes_a_collection_to_an_array()
    {
        $collection = new InMemoryCollection();
        $operator   = new LessThan(42);
        $condition  = new OperatorCondition('user_id', $operator);
        $toggle     = new Toggle('toggling', array($condition));
        $collection->set('some-feature', $toggle);
        $serializer = new InMemoryCollectionSerializer();

        $serialized = $serializer->serialize($collection);

        $this->assertInternalType('array', $serialized);
        $this->assertArrayHasKey('some-feature', $serialized);
        $this->assertArrayHasKey('name', $serialized['some-feature']);
        $this->assertArrayHasKey('conditions', $serialized['some-feature']);
        $this->assertSame('toggling', $serialized['some-feature']['name']);
    }

    /**
     * @test
     */
    public function it_serializes_and_deserializes_a_collection()
    {
        $collection = new InMemoryCollection();
        $operator   = new LessThan(42);
        $condition  = new OperatorCondition('user_id', $operator);
        $toggle     = new Toggle('toggling', array($condition));
        $collection->set('some-feature', $toggle);
        $serializer = new InMemoryCollectionSerializer();

        $serialized = $serializer->serialize($collection);
        $collection2 = $serializer->deserialize($serialized);

        $this->assertEquals($collection, $collection2);
    }
}
