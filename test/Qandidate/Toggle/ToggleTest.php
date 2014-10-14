<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

    /**
     * @test
     */
    public function it_exposes_its_conditions()
    {
        $condition = new OperatorCondition('age', new LessThan(42));
        $condition2 = new OperatorCondition('age', new GreaterThan(42));

        $toggle = new Toggle('some-feature', array($condition, $condition2));

        $actual = $toggle->getConditions();

        $this->assertCount(2, $actual);
        $this->assertEquals($condition, $actual[0]);
        $this->assertEquals($condition2, $actual[1]);
    }

    /**
     * @test
     */
    public function its_status_is_conditionally_active_by_default()
    {
        $toggle = new Toggle('some-feature', array());

        $this->assertEquals(Toggle::CONDITIONALLY_ACTIVE, $toggle->getStatus());
    }

    /**
     * @test
     */
    public function it_can_be_always_activate()
    {
        $toggle = new Toggle('some-feature', array());

        $toggle->activate(Toggle::ALWAYS_ACTIVE);

        $this->assertEquals(Toggle::ALWAYS_ACTIVE, $toggle->getStatus());
    }

    /**
     * @test
     */
    public function it_can_be_inactive()
    {
        $toggle = new Toggle('some-feature', array());

        $toggle->deactivate();

        $this->assertEquals(Toggle::INACTIVE, $toggle->getStatus());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_cannot_be_activated_as_inactive()
    {
        $toggle = new Toggle('some-feature', array());

        $toggle->activate(Toggle::INACTIVE);
    }

    /**
     * @test
     * @dataProvider contextProvider
     */
    public function it_is_active_for_every_context_if_activated_as_always_active($context)
    {
        $conditions = array(
            new OperatorCondition('age', new GreaterThan(42)),
        );

        $toggle = new Toggle('some-feature', $conditions);
        $toggle->activate(Toggle::ALWAYS_ACTIVE);

        $this->assertTrue($toggle->activeFor($context));
    }

    /**
     * @test
     * @dataProvider contextProvider
     */
    public function it_is_not_active_for_every_context_if_inactivated($context)
    {
        $conditions = array(
            new OperatorCondition('age', new GreaterThan(42)),
        );

        $toggle = new Toggle('some-feature', $conditions);
        $toggle->deactivate();

        $this->assertFalse($toggle->activeFor($context));
    }

    public function contextProvider()
    {
        return array(
            array($this->createContext(array('age' => 1337))),
            array($this->createContext(array('age' => 21))),
        );
    }

    private function createContext(array $properties)
    {
        $context = new Context();

        foreach ($properties as $key => $value) {
            $context->set($key, $value);
        }

        return $context;
    }
}
