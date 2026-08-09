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

namespace Qandidate\Toggle\Operator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\TestCase;

class NotInSetTest extends TestCase
{
    #[Test]
    #[DataProvider('valuesNotInSet')]
    public function it_applies_if_value_not_in_set($argument, $values)
    {
        $operator = new NotInSet($values);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public static function valuesNotInSet()
    {
        return [
            [4,     [1, 1, 2, 3, 5, 8]],
            ['foo-bar', ['foo', 'bar']],
        ];
    }

    #[Test]
    #[DataProvider('valuesInSet')]
    public function it_does_not_apply_if_value_in_set($argument, $set)
    {
        $operator = new NotInSet($set);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public static function valuesInSet()
    {
        return [
            [2,     [1, 1, 2, 3]],
            ['qux', ['qux', 'bar']],
        ];
    }

    #[Test]
    #[DataProvider('nullSets')]
    public function it_considers_null_to_not_be_part_of_a_set($argument, $set)
    {
        $operator = new NotInSet($set);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public static function nullSets()
    {
        return [
            [null, [null, 1]],
            [null, [0, 1]],
        ];
    }

    #[Test]
    public function it_exposes_its_values()
    {
        $values = [1, 'foo'];
        $operator = new NotInSet($values);

        $this->assertEquals($values, $operator->getValues());
    }
}
