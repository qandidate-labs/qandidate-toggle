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

use Qandidate\Toggle\TestCase;

class EqualToOperatorTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider integerValues
     */
    public function it_applies_to_integer_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function integerValues()
    {
        return [
            [0,  0],
            [42,  42],
            [-42,  -42],
        ];
    }

    /**
     * @test
     *
     * @dataProvider stringValues
     */
    public function it_applies_to_string_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function stringValues()
    {
        return [
            ['foo', 'foo'],
            ['bar', 'bar'],
            ['baz', 'baz'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider floatValues
     */
    public function it_applies_to_float_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function floatValues()
    {
        return [
            [3.14, 3.14],
            [-3.14, -3.14],
        ];
    }

    /**
     * @test
     *
     * @dataProvider notEqualValues
     */
    public function it_does_not_apply_to_not_equal_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function notEqualValues()
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

    /**
     * @test
     */
    public function it_exposes_its_value()
    {
        $operator = new EqualTo(42);
        $this->assertEquals(42, $operator->getValue());
    }
}
