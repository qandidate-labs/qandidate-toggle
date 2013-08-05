<?php

namespace Qandidate\Trigger\Operator;

use Qandidate\Trigger\TestCase;

class LessThanOperatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider greaterValues
     */
    public function it_does_not_apply_to_greater_values($value, $argument)
    {
        $operator = new LessThan($value);
        $this->assertFalse($operator->appliesTo($argument));
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
    public function it_does_not_apply_to_equal_values($value, $argument)
    {
        $operator = new LessThan($value);
        $this->assertFalse($operator->appliesTo($argument));
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
    public function it_applies_to_smaller_values($value, $argument)
    {
        $operator = new LessThan($value);
        $this->assertTrue($operator->appliesTo($argument));
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
}
