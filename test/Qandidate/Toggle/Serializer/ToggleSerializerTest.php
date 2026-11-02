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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\Condition;
use Qandidate\Toggle\Context;
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Toggle;

#[CoversClass(ToggleSerializer::class)]
class ToggleSerializerTest extends TestCase
{
    #[Test]
    public function it_serializes_a_toggle(): void
    {
        $serializer = $this->createToggleSerializer();

        $operator = new OperatorCondition('user_id', new GreaterThan(42));
        $toggle = new Toggle('some-feature', [$operator]);

        $data = $serializer->serialize($toggle);

        $this->assertSame(
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

    #[Test]
    public function it_deserializes_a_toggle(): void
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
        $this->assertSame(Toggle::CONDITIONALLY_ACTIVE, $toggle->getStatus());
        $this->assertSame(Toggle::STRATEGY_AFFIRMATIVE, $toggle->getStrategy());
    }

    #[Test]
    public function it_throws_exception_on_unsupport_condition(): void
    {
        $this->expectException(\RuntimeException::class);
        $operator = new OtherCondition();
        $toggle = new Toggle('some-feature', [$operator]);

        $serializer = $this->createToggleSerializer();
        $serializer->serialize($toggle);
    }

    /**
     * @param array<string, string> $serialized
     */
    #[Test]
    #[DataProvider('missingKeys')]
    public function it_throws_exception_on_missing_key(array $serialized): void
    {
        $this->expectException(\RuntimeException::class);
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize($serialized);
    }

    /**
     * @return iterable<array{array<string, string>}>
     */
    public static function missingKeys(): iterable
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

    #[Test]
    public function it_throws_exception_if_conditions_key_is_not_an_array(): void
    {
        $this->expectException(\RuntimeException::class);
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(['name' => 'foo', 'status' => 'inactive', 'conditions' => 42]);
    }

    #[Test]
    #[DataProvider('toggleStatuses')]
    public function it_serializes_all_statuses(Toggle $toggle, string $expectedStatus): void
    {
        $serializer = $this->createToggleSerializer();

        $data = $serializer->serialize($toggle);

        $this->assertSame($expectedStatus, $data['status']);
    }

    #[Test]
    #[DataProvider('toggleStatuses')]
    public function it_deserializes_to_the_appropriate_status(Toggle $toggle, string $serializedStatus): void
    {
        $serializer = $this->createToggleSerializer();

        $data = $serializer->serialize($toggle);
        $deserializedToggle = $serializer->deserialize($data);

        $this->assertSame($serializedStatus, $data['status']);
        $this->assertSame($toggle->getStatus(), $deserializedToggle->getStatus());
    }

    /**
     * @return iterable<array{Toggle, string}>
     */
    public static function toggleStatuses(): iterable
    {
        return [
            [self::createAlwaysActiveToggle(), 'always-active'],
            [self::createConditionallyActiveToggle(), 'conditionally-active'],
            [self::createInactiveToggle(), 'inactive'],
        ];
    }

    #[Test]
    public function it_throws_exception_on_invalid_status_data(): void
    {
        $this->expectException(\RuntimeException::class);
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(['name' => 'foo', 'status' => 'invalid', 'conditions' => []]);
    }

    #[Test]
    #[DataProvider('toggleStrategies')]
    public function it_serializes_all_strategies(Toggle $toggle, string $expectedStrategy): void
    {
        $serializer = $this->createToggleSerializer();

        $data = $serializer->serialize($toggle);

        $this->assertSame($expectedStrategy, $data['strategy']);
    }

    #[Test]
    #[DataProvider('toggleStrategies')]
    public function it_deserializes_to_the_appropriate_strategies(Toggle $toggle, string $serializedStrategy): void
    {
        $serializer = $this->createToggleSerializer();

        $data = $serializer->serialize($toggle);
        $deserializedToggle = $serializer->deserialize($data);

        $this->assertSame($serializedStrategy, $data['strategy']);
        $this->assertSame($toggle->getStrategy(), $deserializedToggle->getStrategy());
    }

    #[Test]
    public function it_throws_exception_on_invalid_strategy_data(): void
    {
        $this->expectException(\RuntimeException::class);
        $serializer = $this->createToggleSerializer();

        $serializer->deserialize(['name' => 'foo', 'status' => 'conditionally-active', 'strategy' => 'invalid', 'conditions' => []]);
    }

    /**
     * @return iterable<array{Toggle, string}>
     */
    public static function toggleStrategies(): iterable
    {
        return [
            [self::createAffirmativeToggle(), 'affirmative'],
            [self::createMajorityToggle(), 'majority'],
            [self::createUnanimousToggle(), 'unanimous'],
        ];
    }

    private function createToggleSerializer(): ToggleSerializer
    {
        $operatorSerializer = new OperatorSerializer();
        $operatorConditionSerializer = new OperatorConditionSerializer($operatorSerializer);

        return new ToggleSerializer($operatorConditionSerializer);
    }

    private static function createAlwaysActiveToggle(): Toggle
    {
        $toggle = new Toggle('some-feature', []);
        $toggle->activate(Toggle::ALWAYS_ACTIVE);

        return $toggle;
    }

    private static function createConditionallyActiveToggle(): Toggle
    {
        $toggle = new Toggle('some-feature', []);

        return $toggle;
    }

    private static function createInactiveToggle(): Toggle
    {
        $toggle = new Toggle('some-feature', []);
        $toggle->deactivate();

        return $toggle;
    }

    private static function createAffirmativeToggle(): Toggle
    {
        $toggle = new Toggle('some-feature', [], Toggle::STRATEGY_AFFIRMATIVE);

        return $toggle;
    }

    private static function createMajorityToggle(): Toggle
    {
        $toggle = new Toggle('some-feature', [], Toggle::STRATEGY_MAJORITY);

        return $toggle;
    }

    private static function createUnanimousToggle(): Toggle
    {
        $toggle = new Toggle('some-feature', [], Toggle::STRATEGY_UNANIMOUS);

        return $toggle;
    }
}

class OtherCondition extends Condition
{
    public function holdsFor(Context $context): bool
    {
        return false;
    }
}
