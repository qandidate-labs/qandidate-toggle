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

class InSetTest extends TestCase
{
    /**
     * @test
     * @dataProvider valuesInSet
     */
    public function it_applies_if_value_in_set($argument, $values)
    {
        $operator = new InSet($values);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function valuesInSet()
    {
        return array(
            array(5,     array(1, 1, 2, 3, 5, 8)),
            array('foo', array('foo', 'bar')),
        );
    }

    /**
     * @test
     * @dataProvider valuesNotInSet
     */
    public function it_does_not_apply_if_value_not_in_set($argument, $set)
    {
        $operator = new InSet($set);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function valuesNotInSet()
    {
        return array(
            array(5,     array(1, 1, 2, 3)),
            array('foo', array('qux', 'bar')),
        );
    }

    /**
     * @test
     * @dataProvider nullSets
     */
    public function it_never_accept_NULL_as_part_of_a_set($argument, $set) 
    {
        $operator = new InSet($set);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function nullSets()
    {
        return array(
            array(null, array(null, 1)),
            array(null, array(0, 1)),
        );
    }

    /**
     * @test
     */
    public function it_exposes_its_values()
    {
        $values = array(1, 'foo');
        $operator = new InSet($values);

        $this->assertEquals($values, $operator->getValues());
    }
}
