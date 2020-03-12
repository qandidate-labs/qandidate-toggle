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

use Qandidate\Toggle\Condition;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\OperatorCondition;
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
        $toggle = new Toggle('some-feature', [$operator]);

        $data = $serializer->serialize($toggle);

        $this->assertEquals(
            [
                'name' => 'some-feature',
                'conditions' => [
                    [
                        'name' => 'operator-condition',
                        'key' => 'user_id',
                        'operator' => ['name' => 'greater-than', 'value' => 42],
                    ],
                ],
                'status' => 'conditionally-active',
                'strategy' => 'affirmative',
            ],
            $data
        );
    }

    /**
     * @test
     */
    public function it_deserializes_a_toggle()
    {
        $serializer = $this->createToggleSerializer();

        $toggle = [
            'name' => 'some-feature',
            'conditions' => [
                [
                    'name' => 'operator-condition',
                    'key' => 'user_id',
                    'operator' => ['name' => 'greater-than', 'value' => 42],
                ],
            ],
        ];

        $operator = new OperatorCondition('user_id', new GreaterThan(42));
        $expected = new Toggle('some-feature', [$operator]);

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
        $toggle = new Toggle('some-feature', [$operator]);

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
        return [
            [[]],
            [['name' => '']],
            [['conditions' => '']],
            [['status' => '']],
            [['name' => '', 'conditions' => '']],
            [['name' => '', 'status' => '']],
            [['conditions' => '', 'status' => '']],
            [['strategy' => '']],
        ];
    }

    /**
     * @test
     */
    public function it_throws_exception_if_conditions_key_is_not_an_array()
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(['name' => 'foo', 'status' => 'inactive', 'conditions' => 42]);
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
        $status = $toggle->getStatus();

        $data = $serializer->serialize($toggle);
        $deserializedToggle = $serializer->deserialize($data);

        $this->assertEquals($status, $deserializedToggle->getStatus());
    }

    public function toggleStatuses()
    {
        return [
            [$this->createAlwaysActiveToggle(), 'always-active'],
            [$this->createConditionallyActiveToggle(), 'conditionally-active'],
            [$this->createInactiveToggle(), 'inactive'],
        ];
    }

    /**
     * @test
     */
    public function it_throws_exception_on_invalid_status_data()
    {
        $this->expectException('RuntimeException');
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(['name' => 'foo', 'status' => 'invalid', 'conditions' => []]);
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
        $strategy = $toggle->getStrategy();

        $data = $serializer->serialize($toggle);
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

        $serializer->deserialize(['name' => 'foo', 'status' => 'conditionally-active', 'strategy' => 'invalid', 'conditions' => []]);
    }

    public function toggleStrategies()
    {
        return [
            [$this->createAffirmativeToggle(), 'affirmative'],
            [$this->createMajorityToggle(), 'majority'],
            [$this->createUnanimousToggle(), 'unanimous'],
        ];
    }

    private function createToggleSerializer()
    {
        $operatorSerializer = new OperatorSerializer();
        $operatorConditionSerializer = new OperatorConditionSerializer($operatorSerializer);

        return new ToggleSerializer($operatorConditionSerializer);
    }

    private function createAlwaysActiveToggle()
    {
        $toggle = new Toggle('some-feature', []);
        $toggle->activate(Toggle::ALWAYS_ACTIVE);

        return $toggle;
    }

    private function createConditionallyActiveToggle()
    {
        $toggle = new Toggle('some-feature', []);

        return $toggle;
    }

    private function createInactiveToggle()
    {
        $toggle = new Toggle('some-feature', []);
        $toggle->deactivate();

        return $toggle;
    }

    private function createAffirmativeToggle()
    {
        $toggle = new Toggle('some-feature', [], Toggle::STRATEGY_AFFIRMATIVE);

        return $toggle;
    }

    private function createMajorityToggle()
    {
        $toggle = new Toggle('some-feature', [], Toggle::STRATEGY_MAJORITY);

        return $toggle;
    }

    private function createUnanimousToggle()
    {
        $toggle = new Toggle('some-feature', [], Toggle::STRATEGY_UNANIMOUS);

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
