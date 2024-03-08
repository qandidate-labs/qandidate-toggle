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

use Qandidate\Toggle\TestCase;

class InSetTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider valuesInSet
     */
    public function it_applies_if_value_in_set(int|string $argument, array $values): void
    {
        $operator = new InSet($values);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function valuesInSet(): array
    {
        return [
            [5,     [1, 1, 2, 3, 5, 8]],
            ['foo', ['foo', 'bar']],
        ];
    }

    /**
     * @test
     *
     * @dataProvider valuesNotInSet
     */
    public function it_does_not_apply_if_value_not_in_set(int|string $argument, array $set): void
    {
        $operator = new InSet($set);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function valuesNotInSet(): array
    {
        return [
            [5,     [1, 1, 2, 3]],
            ['foo', ['qux', 'bar']],
        ];
    }

    /**
     * @test
     *
     * @dataProvider nullSets
     */
    public function it_never_accepts_null_as_part_of_a_set(mixed $argument, array $set): void
    {
        $operator = new InSet($set);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function nullSets(): array
    {
        return [
            [null, [null, 1]],
            [null, [0, 1]],
        ];
    }

    /**
     * @test
     */
    public function it_exposes_its_values(): void
    {
        $values = [1, 'foo'];
        $operator = new InSet($values);

        $this->assertEquals($values, $operator->getValues());
    }
}
