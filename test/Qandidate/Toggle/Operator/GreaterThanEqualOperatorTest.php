<?php

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

class GreaterThanEqualOperatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider greaterValues
     */
    public function it_applies_to_greater_values($value, $argument)
    {
        $operator = new GreaterThanEqual($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function greaterValues()
    {
        return array(
            array(42,  43),
            array(42,  1337),
            array(42,  42.1),
            array(0.1, 0.2),
        );
    }

    /**
     * @test
     * @dataProvider equalValues
     */
    public function it_does_applies_to_equal_values($value, $argument)
    {
        $operator = new GreaterThanEqual($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function equalValues()
    {
        return array(
            array(42,   42),
            array(42.1, 42.1),
            array(0.1,  0.1),
        );
    }

    /**
     * @test
     * @dataProvider smallerValues
     */
    public function it_does_not_apply_to_smaller_values($value, $argument)
    {
        $operator = new GreaterThanEqual($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function smallerValues()
    {
        return array(
            array(43,   42),
            array(1337, 42),
            array(42.1, 42),
            array(0.2,  0.1),
        );
    }

    /**
     * @test
     */
    public function it_exposes_its_value()
    {
        $operator = new GreaterThanEqual(42);
        $this->assertEquals(42, $operator->getValue());
    }
}
