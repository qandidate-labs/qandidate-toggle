<?php

namespace Qandidate\Toggle\Operator;

use Qandidate\Toggle\TestCase;

class ContainsOperatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider stringBeginningWith
     */
    public function it_applies_to_strings_beginning_with($value, $argument)
    {
        $operator = new Contains($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function stringBeginningWith()
    {
        return array(
            array("foo",  "foobar"),
            array('bar',  'barbaz'),
        );
    }

    /**
     * @test
     * @dataProvider stringEndingWith
     */
    public function it_applies_to_strings_ending_with($value, $argument)
    {
        $operator = new Contains($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function stringEndingWith()
    {
        return array(
            array("bar", "foobar"),
            array('baz', 'barbaz'),
        );
    }

    /**
     * @test
     * @dataProvider equalString
     */
    public function it_applies_to_equal_strings($value, $argument)
    {
        $operator = new Contains($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function equalString()
    {
        return array(
            array("foobar", "foobar"),
            array("barbaz", 'barbaz'),
        );
    }

    /**
     * @test
     * @dataProvider stringNotContaining
     */
    public function it_does_not_apply_to_strings_not_containing($value, $argument)
    {
        $operator = new Contains($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function stringNotContaining()
    {
        return array(
            array("foo", "bar"),
            array("bar", 'baz'),
        );
    }

    /**
     * @test
     */
    public function it_exposes_its_value()
    {
        $operator = new Contains("foobar");
        $this->assertEquals("foobar", $operator->getValue());
    }
} 