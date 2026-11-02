<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Qandidate\Toggle;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(Context::class)]
class ContextTest extends TestCase
{
    #[Test]
    public function it_sets_a_value(): void
    {
        $context = new Context();
        $context->set('foo', 'bar');

        $this->assertSame('bar', $context->get('foo'));
    }

    #[Test]
    #[DataProvider('validValues')]
    public function it_exposes_whether_it_has_a_value(mixed $value): void
    {
        $context = new Context();
        $context->set('foo', $value);

        $this->assertTrue($context->has('foo'));
    }

    /**
     * @return iterable<array{mixed}>
     */
    public static function validValues(): iterable
    {
        return [
            [42],
            ['bar'],
            [null],
            [true],
            [false],
            [0.1],
        ];
    }

    #[Test]
    public function it_does_not_have_a_value_that_was_never_set(): void
    {
        $context = new Context();

        $this->assertFalse($context->has('foo'));
    }
}
