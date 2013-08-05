<?php

namespace Qandidate\Trigger\Operator;

use Qandidate\Trigger\TestCase;

class PercentageTest extends TestCase
{
    /**
     * @test
     * @dataProvider valuesInPercentage
     */
    public function it_applies_if_value_in_percentage($percentage, $argument)
    {
        $operator = new Percentage($percentage);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function valuesInPercentage()
    {
        return array(
            array(5, 4),
            array(5, 104),
            array(5, 1004),
            array(5, 1000),
            array(5, 1001),
        );
    }

    /**
     * @test
     * @dataProvider valuesNotInPercentage
     */
    public function it_does_not_apply_if_value_not_in_percentage($percentage, $argument)
    {
        $operator = new Percentage($percentage);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function valuesNotInPercentage()
    {
        return array(
            array(5, 5),
            array(5, 6),
            array(5, 106),
            array(5, 1006),
        );
    }

    /**
     * @test
     * @dataProvider valuesInPercentageShifted
     */
    public function it_applies_if_value_in_shifted_percentage($percentage, $argument)
    {
        $operator = new Percentage($percentage, 42);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function valuesInPercentageShifted()
    {
        return array(
            array(5, 46),
            array(5, 146),
            array(5, 1046),
            array(5, 1046),
            array(5, 1046),
        );
    }

    /**
     * @test
     * @dataProvider valuesNotInPercentageShifted
     */
    public function it_does_not_apply_if_value_in_shifted_percentage($percentage, $argument)
    {
        $operator = new Percentage($percentage, 42);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function valuesNotInPercentageShifted()
    {
        return array(
            array(5, 47),
            array(5, 48),
            array(5, 148),
            array(5, 1048),
        );
    }
}
