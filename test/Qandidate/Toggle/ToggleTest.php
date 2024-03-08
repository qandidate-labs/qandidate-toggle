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

namespace Qandidate\Toggle;

use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\Operator\LessThan;

class ToggleTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_active_if_one_of_the_conditions_holds_in_affirmative_strategy(): void
    {
        $conditions = [
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('age', new GreaterThan(42)),
        ];

        $context = new Context();
        $context->set('age', 84);

        $toggle = new Toggle('some-feature', $conditions);

        $this->assertTrue($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_is_inactive_if_none_of_the_conditions_hold_in_affirmative_strategy(): void
    {
        $conditions = [
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('age', new GreaterThan(42)),
        ];

        $context = new Context();
        $context->set('age', 42);

        $toggle = new Toggle('some-feature', $conditions);

        $this->assertFalse($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_is_active_if_more_than_half_of_the_conditions_hold_in_majority_strategy(): void
    {
        $conditions = [
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('height', new GreaterThan(5.7)),
            new OperatorCondition('weight', new GreaterThan(154)),
        ];

        $context = new Context();
        $context->set('age', 40);
        $context->set('height', 6);
        $context->set('weight', 150);

        $toggle = new Toggle('some-feature', $conditions, Toggle::STRATEGY_MAJORITY);

        $this->assertTrue($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_is_inactive_if_more_than_half_of_the_conditions_do_not_hold_in_majority_strategy(): void
    {
        $conditions = [
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('height', new GreaterThan(5.7)),
            new OperatorCondition('weight', new GreaterThan(154)),
        ];

        $context = new Context();
        $context->set('age', 40);
        $context->set('height', 5.6);
        $context->set('weight', 150);

        $toggle = new Toggle('some-feature', $conditions, Toggle::STRATEGY_MAJORITY);

        $this->assertFalse($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_is_inactive_if_exactly_half_of_the_conditions_hold_in_majority_strategy(): void
    {
        $conditions = [
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('height', new GreaterThan(5.7)),
        ];

        $context = new Context();
        $context->set('age', 40);
        $context->set('height', 5.6);

        $toggle = new Toggle('some-feature', $conditions, Toggle::STRATEGY_MAJORITY);

        $this->assertFalse($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_is_active_if_all_the_conditions_hold_in_unanimous_strategy(): void
    {
        $conditions = [
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('height', new GreaterThan(5.7)),
        ];

        $context = new Context();
        $context->set('age', 40);
        $context->set('height', 5.8);

        $toggle = new Toggle('some-feature', $conditions, Toggle::STRATEGY_UNANIMOUS);

        $this->assertTrue($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_is_inactive_if_one_of_the_conditions_do_not_hold_in_unanimous_strategy(): void
    {
        $conditions = [
            new OperatorCondition('age', new LessThan(42)),
            new OperatorCondition('height', new GreaterThan(5.7)),
        ];

        $context = new Context();
        $context->set('age', 40);
        $context->set('height', 5.6);

        $toggle = new Toggle('some-feature', $conditions, Toggle::STRATEGY_UNANIMOUS);

        $this->assertFalse($toggle->activeFor($context));
    }

    /**
     * @test
     */
    public function it_exposes_its_name(): void
    {
        $toggle = new Toggle('some-feature', []);

        $this->assertEquals('some-feature', $toggle->getName());
    }

    /**
     * @test
     */
    public function it_exposes_its_conditions(): void
    {
        $condition = new OperatorCondition('age', new LessThan(42));
        $condition2 = new OperatorCondition('age', new GreaterThan(42));

        $toggle = new Toggle('some-feature', [$condition, $condition2]);

        $actual = $toggle->getConditions();

        $this->assertCount(2, $actual);
        $this->assertEquals($condition, $actual[0]);
        $this->assertEquals($condition2, $actual[1]);
    }

    /**
     * @test
     */
    public function its_status_is_conditionally_active_by_default(): void
    {
        $toggle = new Toggle('some-feature', []);

        $this->assertEquals(Toggle::CONDITIONALLY_ACTIVE, $toggle->getStatus());
    }

    /**
     * @test
     */
    public function its_strategy_is_affirmative_by_default(): void
    {
        $toggle = new Toggle('some-feature', []);

        $this->assertEquals(Toggle::STRATEGY_AFFIRMATIVE, $toggle->getStrategy());
    }

    /**
     * @test
     */
    public function it_can_be_always_activate(): void
    {
        $toggle = new Toggle('some-feature', []);

        $toggle->activate(Toggle::ALWAYS_ACTIVE);

        $this->assertEquals(Toggle::ALWAYS_ACTIVE, $toggle->getStatus());
    }

    /**
     * @test
     */
    public function it_can_be_inactive(): void
    {
        $toggle = new Toggle('some-feature', []);

        $toggle->deactivate();

        $this->assertEquals(Toggle::INACTIVE, $toggle->getStatus());
    }

    /**
     * @test
     */
    public function it_cannot_be_activated_as_inactive(): void
    {
        $this->expectException('InvalidArgumentException');
        $toggle = new Toggle('some-feature', []);

        $toggle->activate(Toggle::INACTIVE);
    }

    /**
     * @test
     */
    public function it_cannot_be_set_with_an_non_existing_strategy(): void
    {
        $this->expectException('InvalidArgumentException');
        new Toggle('some-feature', [], -1);
    }

    /**
     * @test
     *
     * @dataProvider contextProvider
     */
    public function it_is_active_for_every_context_if_activated_as_always_active(Context $context): void
    {
        $conditions = [
            new OperatorCondition('age', new GreaterThan(42)),
        ];

        $toggle = new Toggle('some-feature', $conditions);
        $toggle->activate(Toggle::ALWAYS_ACTIVE);

        $this->assertTrue($toggle->activeFor($context));
    }

    /**
     * @test
     *
     * @dataProvider contextProvider
     */
    public function it_is_not_active_for_every_context_if_inactivated(Context $context): void
    {
        $conditions = [
            new OperatorCondition('age', new GreaterThan(42)),
        ];

        $toggle = new Toggle('some-feature', $conditions);
        $toggle->deactivate();

        $this->assertFalse($toggle->activeFor($context));
    }

    public function contextProvider(): array
    {
        return [
            [$this->createContext(['age' => 1337])],
            [$this->createContext(['age' => 21])],
        ];
    }

    private function createContext(array $properties): Context
    {
        $context = new Context();

        foreach ($properties as $key => $value) {
            $context->set($key, $value);
        }

        return $context;
    }
}
