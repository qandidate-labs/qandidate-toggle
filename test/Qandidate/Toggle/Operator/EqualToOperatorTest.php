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

namespace Qandidate\Toggle\Operator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\TestCase;

#[CoversClass(EqualTo::class)]
class EqualToOperatorTest extends TestCase
{
    #[Test]
    #[DataProvider('integerValues')]
    public function it_applies_to_integer_values(mixed $value, mixed $argument): void
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, mixed}>
     */
    public static function integerValues(): iterable
    {
        return [
            [0,  0],
            [42,  42],
            [-42,  -42],
        ];
    }

    #[Test]
    #[DataProvider('stringValues')]
    public function it_applies_to_string_values(mixed $value, mixed $argument): void
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, mixed}>
     */
    public static function stringValues(): iterable
    {
        return [
            ['foo', 'foo'],
            ['bar', 'bar'],
            ['baz', 'baz'],
        ];
    }

    #[Test]
    #[DataProvider('floatValues')]
    public function it_applies_to_float_values(mixed $value, mixed $argument): void
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, mixed}>
     */
    public static function floatValues(): iterable
    {
        return [
            [3.14, 3.14],
            [-3.14, -3.14],
        ];
    }

    #[Test]
    #[DataProvider('notEqualValues')]
    public function it_does_not_apply_to_not_equal_values(mixed $value, mixed $argument): void
    {
        $operator = new EqualTo($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, mixed}>
     */
    public static function notEqualValues(): iterable
    {
        return [
            [42,   43],
            [-42, -43],
            [-42.1, -43.1],
            [false, 0],
            [null, 0],
            [true, 1],
            ['0', 0],
        ];
    }

    #[Test]
    public function it_exposes_its_value(): void
    {
        $operator = new EqualTo(42);
        $this->assertSame(42, $operator->getValue());
    }
}
