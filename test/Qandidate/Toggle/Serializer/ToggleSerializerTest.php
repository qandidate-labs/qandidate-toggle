<?php

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\Condition;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\Toggle;

class ToggleSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function it_serializes_a_toggle()
    {
        $serializer = $this->createToggleSerializer();

        $operator = new OperatorCondition('user_id', new GreaterThan(42));
        $toggle   = new Toggle('some-feature', array($operator));

        $data = $serializer->serialize($toggle);

        $this->assertEquals(
            array(
                'name' => 'some-feature',
                'conditions' => array(
                    array(
                        'name' => 'operator-condition',
                        'key' => 'user_id',
                        'operator' => array('name' => 'greater-than', 'value' => 42),
                    ),
                )
            ),
            $data
        );
    }

    /**
     * @test
     */
    public function it_deserializes_a_toggle()
    {
        $serializer = $this->createToggleSerializer();

        $toggle = array(
            'name' => 'some-feature',
            'conditions' => array(
                array(
                    'name' => 'operator-condition',
                    'key' => 'user_id',
                    'operator' => array('name' => 'greater-than', 'value' => 42),
                ),
            )
        );

        $operator = new OperatorCondition('user_id', new GreaterThan(42));
        $expected = new Toggle('some-feature', array($operator));

        $toggle = $serializer->deserialize($toggle);

        $this->assertEquals($expected, $toggle);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_throws_exception_on_unsupport_condition()
    {
        $operator = new OtherCondition();
        $toggle   = new Toggle('some-feature', array($operator));

        $serializer = $this->createToggleSerializer();
        $serializer->serialize($toggle);
    }

    /**
     * @test
     * @dataProvider missingKeys
     * @expectedException RuntimeException
     */
    public function it_throws_exception_on_missing_key($serialized)
    {
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize($serialized);
    }

    public function missingKeys()
    {
        return array(
            array(array()),
            array(array('name')),
            array(array('conditions')),
        );
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function it_throws_exception_if_conditions_key_is_not_an_array()
    {
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(array('name' => 'foo', 'conditions' => 42));
    }

    private function createToggleSerializer()
    {
        $operatorSerializer          = new OperatorSerializer();
        $operatorConditionSerializer = new OperatorConditionSerializer($operatorSerializer);

        return new ToggleSerializer($operatorConditionSerializer);
    }
}

class OtherCondition extends Condition
{
    public function holdsFor(Context $context)
    {
        return false;
    }
}
