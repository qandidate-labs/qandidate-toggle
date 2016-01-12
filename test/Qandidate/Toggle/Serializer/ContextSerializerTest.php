<?php

namespace Qandidate\Toggle\Serializer;

class ContextSerializerTest extends TestCase
{
    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_throws_exception_on_empty_request()
    {
        $serialized = array();

        $serializer = new ContextSerializer();
        $serializer->deserialize($serialized);
    }

    /**
     * @test
     */
    public function it_deserializes_an_empty_context()
    {
        $serialized = array(
            'context' => array()
        );

        $serializer = new ContextSerializer();
        $context = $serializer->deserialize($serialized);

        $this->assertEmpty($context->toArray());
    }

    /**
     * @test
     */
    public function it_deserializes_a_context()
    {
        $serialized = array(
            'context' => array(
                'foo' => 'bar',
                'bar' => 'baz'
            )
        );

        $serializer = new ContextSerializer();
        $context = $serializer->deserialize($serialized);

        $this->assertTrue($context->has('foo'));
        $this->assertTrue($context->has('bar'));
        $this->assertEquals('bar', $context->get('foo'));
        $this->assertEquals('baz', $context->get('bar'));
        $this->assertCount(2, $context->toArray());
    }
} 