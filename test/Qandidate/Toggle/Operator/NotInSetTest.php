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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\TestCase;

#[CoversClass(NotInSet::class)]
class NotInSetTest extends TestCase
{
    /**
     * @param list<mixed> $values
     */
    #[Test]
    #[DataProvider('valuesNotInSet')]
    public function it_applies_if_value_not_in_set(mixed $argument, array $values): void
    {
        $operator = new NotInSet($values);
        $this->assertTrue($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, list<mixed>}>
     */
    public static function valuesNotInSet(): iterable
    {
        return [
            [4,     [1, 1, 2, 3, 5, 8]],
            ['foo-bar', ['foo', 'bar']],
        ];
    }

    /**
     * @param list<mixed> $set
     */
    #[Test]
    #[DataProvider('valuesInSet')]
    public function it_does_not_apply_if_value_in_set(mixed $argument, array $set): void
    {
        $operator = new NotInSet($set);
        $this->assertFalse($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, list<mixed>}>
     */
    public static function valuesInSet(): iterable
    {
        return [
            [2,     [1, 1, 2, 3]],
            ['qux', ['qux', 'bar']],
        ];
    }

    /**
     * @param list<mixed> $set
     */
    #[Test]
    #[DataProvider('nullSets')]
    public function it_considers_null_to_not_be_part_of_a_set(mixed $argument, array $set): void
    {
        $operator = new NotInSet($set);
        $this->assertTrue($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{mixed, list<mixed>}>
     */
    public static function nullSets(): iterable
    {
        return [
            [null, [null, 1]],
            [null, [0, 1]],
        ];
    }

    #[Test]
    public function it_exposes_its_values(): void
    {
        $values = [1, 'foo'];
        $operator = new NotInSet($values);

        $this->assertSame($values, $operator->getValues());
    }
}
