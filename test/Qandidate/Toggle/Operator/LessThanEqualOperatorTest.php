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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\TestCase;

class LessThanEqualOperatorTest extends TestCase
{
    #[Test]
    #[DataProvider('greaterValues')]
    public function it_does_not_apply_to_greater_values($value, $argument)
    {
        $operator = new LessThanEqual($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public static function greaterValues()
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
    public function it_applies_to_equal_values($value, $argument)
    {
        $operator = new LessThanEqual($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public static function equalValues()
    {
        return [
            [42,   42],
            [42.1, 42.1],
            [0.1,  0.1],
        ];
    }

    #[Test]
    #[DataProvider('smallerValues')]
    public function it_applies_to_smaller_values($value, $argument)
    {
        $operator = new LessThanEqual($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public static function smallerValues()
    {
        return [
            [43,   42],
            [1337, 42],
            [42.1, 42],
            [0.2,  0.1],
        ];
    }

    #[Test]
    public function it_exposes_its_value()
    {
        $operator = new LessThanEqual(42);
        $this->assertEquals(42, $operator->getValue());
    }
}
