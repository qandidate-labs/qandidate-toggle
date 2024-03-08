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

class PercentageTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider valuesInPercentage
     */
    public function it_applies_if_value_in_percentage(int $percentage, int $argument): void
    {
        $operator = new Percentage($percentage);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function valuesInPercentage(): array
    {
        return [
            [5, 4],
            [5, 104],
            [5, 1004],
            [5, 1000],
            [5, 1001],
        ];
    }

    /**
     * @test
     *
     * @dataProvider valuesNotInPercentage
     */
    public function it_does_not_apply_if_value_not_in_percentage(int $percentage, int $argument): void
    {
        $operator = new Percentage($percentage);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function valuesNotInPercentage(): array
    {
        return [
            [5, 5],
            [5, 6],
            [5, 106],
            [5, 1006],
        ];
    }

    /**
     * @test
     *
     * @dataProvider valuesInPercentageShifted
     */
    public function it_applies_if_value_in_shifted_percentage(int $percentage, int $argument): void
    {
        $operator = new Percentage($percentage, 42);
        $this->assertTrue($operator->appliesTo($argument));
    }

    public function valuesInPercentageShifted(): array
    {
        return [
            [5, 46],
            [5, 146],
            [5, 1046],
            [5, 1046],
            [5, 1046],
        ];
    }

    /**
     * @test
     *
     * @dataProvider valuesNotInPercentageShifted
     */
    public function it_does_not_apply_if_value_in_shifted_percentage(int $percentage, int $argument): void
    {
        $operator = new Percentage($percentage, 42);
        $this->assertFalse($operator->appliesTo($argument));
    }

    public function valuesNotInPercentageShifted(): array
    {
        return [
            [5, 47],
            [5, 48],
            [5, 148],
            [5, 1048],
        ];
    }

    /**
     * @test
     */
    public function it_exposes_its_percentage_and_shift(): void
    {
        $operator = new Percentage(42, 5);

        $this->assertEquals(42, $operator->getPercentage());
        $this->assertEquals(5, $operator->getShift());
    }
}
