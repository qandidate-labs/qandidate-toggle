<?php

namespace Qandidate\Toggle;

use Qandidate\Toggle\Operator\GreaterThan;

class OperatorConditionTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_false_if_context_does_not_contain_key()
    {
        $condition = new OperatorCondition('age', new GreaterThan(42));
        $context   = new Context();

        $this->assertFalse($condition->holdsFor($context));
    }

    /**
     * @test
     * @dataProvider valueAvailable
     */
    public function it_returns_whether_it_operator_holds_for_the_value_if_available($value, $expected)
    {
        $condition = new OperatorCondition('age', new GreaterThan(42));
        $context   = new Context();
        $context->set('age', $value);

        $this->assertEquals($expected, $condition->holdsFor($context));
    }

    public function valueAvailable()
    {
        return array(
            array(24, false),
            array(84, true),
        );
    }
}
