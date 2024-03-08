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

namespace Qandidate\Toggle\Operator;

use Qandidate\Toggle\TestCase;

class LessThanEqualOperatorTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider greaterValues
     */
    public function it_does_not_apply_to_greater_values(int|float $value, int|float $argument): void
    {
        $operator = new LessThanEqual($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function greaterValues(): array
    {
        return [
            [42,  43],
            [42,  1337],
            [42,  42.1],
            [0.1, 0.2],
        ];
    }

    /**
     * @test
     *
     * @dataProvider equalValues
     */
    public function it_applies_to_equal_values(int|float $value, int|float $argument): void
    {
        $operator = new LessThanEqual($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function equalValues(): array
    {
        return [
            [42,   42],
            [42.1, 42.1],
            [0.1,  0.1],
        ];
    }

    /**
     * @test
     *
     * @dataProvider smallerValues
     */
    public function it_applies_to_smaller_values(int|float $value, int|float $argument): void
    {
        $operator = new LessThanEqual($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function smallerValues(): array
    {
        return [
            [43,   42],
            [1337, 42],
            [42.1, 42],
            [0.2,  0.1],
        ];
    }

    /**
     * @test
     */
    public function it_exposes_its_value(): void
    {
        $operator = new LessThanEqual(42);
        $this->assertEquals(42, $operator->getValue());
    }
}
