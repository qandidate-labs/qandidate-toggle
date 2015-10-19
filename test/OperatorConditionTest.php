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

    /**
     * @test
     */
    public function it_exposes_its_key_and_operator()
    {
        $key       = 'age';
        $operator  = new GreaterThan(42);
        $condition = new OperatorCondition($key, $operator);

        $this->assertEquals($key, $condition->getKey());
        $this->assertEquals($operator, $condition->getOperator());
    }
}
