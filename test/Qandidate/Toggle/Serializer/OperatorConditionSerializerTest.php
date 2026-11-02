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
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\OperatorCondition;

#[CoversClass(OperatorConditionSerializer::class)]
class OperatorConditionSerializerTest extends TestCase
{
    #[Test]
    public function it_serializes_an_operator(): void
    {
        $serializer = $this->createOperatorConditionSerializer();

        $operator = new OperatorCondition('user_id', new GreaterThan(42));
        $data = $serializer->serialize($operator);

        $this->assertSame(
            [
                'name' => 'operator-condition',
                'key' => 'user_id',
                'operator' => ['name' => 'greater-than', 'value' => 42],
            ],
            $data
        );
    }

    #[Test]
    public function it_deserializes_an_operator(): void
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

    #[Test]
    public function it_throws_exception_on_unknown_name(): void
    {
        $this->expectException(\RuntimeException::class);
        $serializer = $this->createOperatorConditionSerializer();

        $serialized = [
            'name' => 'unknown-name',
            'key' => 'user_id',
            'operator' => ['name' => 'greater-than', 'value' => 42],
        ];

        $serializer->deserialize($serialized);
    }

    /**
     * @param array<mixed> $serialized
     */
    #[Test]
    #[DataProvider('missingKeys')]
    public function it_throws_exception_on_missing_key(array $serialized): void
    {
        $this->expectException(\RuntimeException::class);
        $serializer = $this->createOperatorConditionSerializer();

        $serializer->deserialize($serialized);
    }

    /**
     * @return iterable<array{list<string>}>
     */
    public static function missingKeys(): iterable
    {
        return [
            [[]],
            [['name']],
            [['name', 'key']],
            [['name', 'operator']],
        ];
    }

    private function createOperatorConditionSerializer(): OperatorConditionSerializer
    {
        $operatorSerializer = new OperatorSerializer();

        return new OperatorConditionSerializer($operatorSerializer);
    }
}
