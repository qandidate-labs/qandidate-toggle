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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\TestCase;

#[CoversClass(GreaterThan::class)]
class GreaterThanOperatorTest extends TestCase
{
    #[Test]
    #[DataProvider('greaterValues')]
    public function it_applies_to_greater_values(mixed $value, mixed $argument): void
    {
        $operator = new GreaterThan($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, mixed}>
     */
    public static function greaterValues(): iterable
    {
        return [
            [42,  43],
            [42,  1337],
            [42,  42.1],
            [0.1, 0.2],
        ];
    }

    #[Test]
    #[DataProvider('equalValues')]
    public function it_does_not_apply_to_equal_values(mixed $value, mixed $argument): void
    {
        $operator = new GreaterThan($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, mixed}>
     */
    public static function equalValues(): iterable
    {
        return [
            [42,   42],
            [42.1, 42.1],
            [0.1,  0.1],
        ];
    }

    #[Test]
    #[DataProvider('smallerValues')]
    public function it_does_not_apply_to_smaller_values(mixed $value, mixed $argument): void
    {
        $operator = new GreaterThan($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, mixed}>
     */
    public static function smallerValues(): iterable
    {
        return [
            [43,   42],
            [1337, 42],
            [42.1, 42],
            [0.2,  0.1],
        ];
    }

    #[Test]
    public function it_exposes_its_value(): void
    {
        $operator = new GreaterThan(42);
        $this->assertSame(42, $operator->getValue());
    }
}
