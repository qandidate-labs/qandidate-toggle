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
                ),
                'status' => 'conditionally-active',
                'strategy' => 'affirmative'
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
            ),
        );

        $operator = new OperatorCondition('user_id', new GreaterThan(42));
        $expected = new Toggle('some-feature', array($operator));

        $toggle = $serializer->deserialize($toggle);

        $this->assertEquals($expected, $toggle);
        $this->assertEquals(Toggle::CONDITIONALLY_ACTIVE, $toggle->getStatus());
        $this->assertEquals(Toggle::STRATEGY_AFFIRMATIVE, $toggle->getStrategy());
    }

    /**
     * @test
     */
    public function it_throws_exception_on_unsupport_condition()
    {
        $this->expectException('RuntimeException');
        $operator = new OtherCondition();
        $toggle   = new Toggle('some-feature', array($operator));

        $serializer = $this->createToggleSerializer();
        $serializer->serialize($toggle);
    }

    /**
     * @test
     * @dataProvider missingKeys
     */
    public function it_throws_exception_on_missing_key($serialized)
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize($serialized);
    }

    public function missingKeys()
    {
        return array(
            array(array()),
            array(array('name' => '')),
            array(array('conditions' => '')),
            array(array('status' => '')),
            array(array('name' => '', 'conditions' => '')),
            array(array('name' => '', 'status' => '')),
            array(array('conditions' => '', 'status' => '')),
            array(array('strategy' => ''))
        );
    }

    /**
     * @test
     */
    public function it_throws_exception_if_conditions_key_is_not_an_array()
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(array('name' => 'foo', 'status' => 'inactive', 'conditions' => 42));
    }

    /**
     * @test
     * @dataProvider toggleStatuses
     */
    public function it_serializes_all_statuses($toggle, $expectedStatus)
    {
        $serializer = $this->createToggleSerializer();

        $data = $serializer->serialize($toggle);

        $this->assertEquals($expectedStatus, $data['status']);
    }

    /**
     * @test
     * @dataProvider toggleStatuses
     */
    public function it_deserializes_to_the_appropriate_status($toggle)
    {
        $serializer = $this->createToggleSerializer();
        $status     = $toggle->getStatus();

        $data               = $serializer->serialize($toggle);
        $deserializedToggle = $serializer->deserialize($data);

        $this->assertEquals($status, $deserializedToggle->getStatus());
    }

    public function toggleStatuses()
    {
        return array(
            array($this->createAlwaysActiveToggle(), 'always-active'),
            array($this->createConditionallyActiveToggle(), 'conditionally-active'),
            array($this->createInactiveToggle(), 'inactive'),
        );
    }

    /**
     * @test
     */
    public function it_throws_exception_on_invalid_status_data()
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(array('name' => 'foo', 'status' => 'invalid', 'conditions' => array()));
    }

    /**
     * @test
     * @dataProvider toggleStrategies
     */
    public function it_serializes_all_strategies($toggle, $expectedStrategy)
    {
        $serializer = $this->createToggleSerializer();

        $data = $serializer->serialize($toggle);

        $this->assertEquals($expectedStrategy, $data['strategy']);
    }

    /**
     * @test
     * @dataProvider toggleStrategies
     */
    public function it_deserializes_to_the_appropriate_strategies($toggle)
    {
        $serializer = $this->createToggleSerializer();
        $strategy   = $toggle->getStrategy();

        $data               = $serializer->serialize($toggle);
        $deserializedToggle = $serializer->deserialize($data);

        $this->assertEquals($strategy, $deserializedToggle->getStrategy());
    }

    /**
     * @test
     */
    public function it_throws_exception_on_invalid_strategy_data()
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(array('name' => 'foo', 'status' => 'conditionally-active', 'strategy' => 'invalid', 'conditions' => array()));
    }

    public function toggleStrategies()
    {
        return array(
            array($this->createAffirmativeToggle(), 'affirmative'),
            array($this->createMajorityToggle(), 'majority'),
            array($this->createUnanimousToggle(), 'unanimous'),
        );
    }

    private function createToggleSerializer()
    {
        $operatorSerializer          = new OperatorSerializer();
        $operatorConditionSerializer = new OperatorConditionSerializer($operatorSerializer);

        return new ToggleSerializer($operatorConditionSerializer);
    }

    private function createAlwaysActiveToggle()
    {
        $toggle = new Toggle('some-feature', array());
        $toggle->activate(Toggle::ALWAYS_ACTIVE);

        return $toggle;
    }

    private function createConditionallyActiveToggle()
    {
        $toggle = new Toggle('some-feature', array());

        return $toggle;
    }

    private function createInactiveToggle()
    {
        $toggle = new Toggle('some-feature', array());
        $toggle->deactivate();

        return $toggle;
    }

    private function createAffirmativeToggle()
    {
        $toggle = new Toggle('some-feature', array(), Toggle::STRATEGY_AFFIRMATIVE);

        return $toggle;
    }

    private function createMajorityToggle()
    {
        $toggle = new Toggle('some-feature', array(), Toggle::STRATEGY_MAJORITY);

        return $toggle;
    }

    private function createUnanimousToggle()
    {
        $toggle = new Toggle('some-feature', array(), Toggle::STRATEGY_UNANIMOUS);

        return $toggle;
    }
}

class OtherCondition extends Condition
{
    public function holdsFor(Context $context)
    {
        return false;
    }
}
