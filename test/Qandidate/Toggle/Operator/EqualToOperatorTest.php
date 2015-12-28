<?php

namespace Qandidate\Toggle\Operator;

use Qandidate\Toggle\TestCase;

class EqualToOperatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider integerValues
     */
    public function it_applies_to_integer_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function integerValues()
    {
        return array(
            array(0,  0),
            array(42,  42),
            array(-42,  -42),
        );
    }

    /**
     * @test
     * @dataProvider stringValues
     */
    public function it_applies_to_string_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function stringValues()
    {
        return array(
            array("foo", "foo"),
            array('bar', 'bar'),
            array("baz", 'baz'),
        );
    }

    /**
     * @test
     * @dataProvider floatValues
     */
    public function it_applies_to_float_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function floatValues()
    {
        return array(
            array(3.14, 3.14),
            array(-3.14, -3.14),
        );
    }

    /**
     * @test
     * @dataProvider notEqualValues
     */
    public function it_does_not_apply_to_not_equal_values($value, $argument)
    {
        $operator = new EqualTo($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function notEqualValues()
    {
        return array(
            array(42,   43),
            array(-42, -43),
            array(-42.1, -43.1),
            array(false, 0),
            array(null, 0),
            array(true, 1),
            array("0", 0),
        );
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
