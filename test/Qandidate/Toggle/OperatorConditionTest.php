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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\Operator\GreaterThan;

#[CoversClass(OperatorCondition::class)]
class OperatorConditionTest extends TestCase
{
    #[Test]
    public function it_returns_false_if_context_does_not_contain_key(): void
    {
        $condition = new OperatorCondition('age', new GreaterThan(42));
        $context = new Context();

        $this->assertFalse($condition->holdsFor($context));
    }

    #[Test]
    #[DataProvider('valueAvailable')]
    public function it_returns_whether_it_operator_holds_for_the_value_if_available(int $value, bool $expected): void
    {
        $condition = new OperatorCondition('age', new GreaterThan(42));
        $context = new Context();
        $context->set('age', $value);

        $this->assertSame($expected, $condition->holdsFor($context));
    }

    /**
     * @return iterable<array{int, bool}>
     */
    public static function valueAvailable(): iterable
    {
        return [
            [24, false],
            [84, true],
        ];
    }

    #[Test]
    public function it_exposes_its_key_and_operator(): void
    {
        $key = 'age';
        $operator = new GreaterThan(42);
        $condition = new OperatorCondition($key, $operator);

        $this->assertSame($key, $condition->getKey());
        $this->assertSame($operator, $condition->getOperator());
    }
}
