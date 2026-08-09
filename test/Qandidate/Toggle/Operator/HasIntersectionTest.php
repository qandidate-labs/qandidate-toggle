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

class HasIntersectionTest extends TestCase
{
    #[Test]
    #[DataProvider('valuesNotMatching')]
    public function it_not_applies_to_set_not_matching_values($values, $argument)
    {
        $operator = new HasIntersection($values);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public static function valuesNotMatching()
    {
        return [
            [[4],     [1, 2, 3]],
            [[5, 6],  [1, 2, 3]],
            [['foo'], ['qux', 'bar']],
        ];
    }

    #[Test]
    #[DataProvider('valuesMatching')]
    public function it_applies_to_set_matching_values($values, $argument)
    {
        $operator = new HasIntersection($values);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public static function valuesMatching()
    {
        return [
            [[1],     [1, 2, 3]],
            [[2],     [1, 2, 3]],
            [[3, 2],  [1, 2, 3]],
            [['bar'], ['foo', 'bar']],
        ];
    }

    #[Test]
    public function it_exposes_its_value()
    {
        $operator = new HasIntersection(['a', 'b', 'c']);
        $this->assertEquals(['a', 'b', 'c'], $operator->getValues());
    }
}
