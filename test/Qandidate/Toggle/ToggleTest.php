<?php

namespace Qandidate\Toggle;

use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\Operator\LessThan;

class ToggleTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_active_if_one_of_the_conditions_holds()
    {
        $conditions = array(
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('age', new GreaterThan(42)),
        );

        $context   = new Context();
        $context->set('age', 84);

        $toggle = new Toggle('some-feature', $conditions);

        $this->assertTrue($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_is_inactive_if_none_of_the_conditions_hold()
    {
        $conditions = array(
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('age', new GreaterThan(42)),
        );

        $context   = new Context();
        $context->set('age', 42);

        $toggle = new Toggle('some-feature', $conditions);

        $this->assertFalse($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_exposes_its_name()
    {
        $toggle = new Toggle('some-feature', array());

        $this->assertEquals('some-feature', $toggle->getName());
    }
}
