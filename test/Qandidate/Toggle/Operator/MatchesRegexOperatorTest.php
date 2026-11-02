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

namespace Qandidate\Toggle\Operator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Qandidate\Toggle\TestCase;

#[CoversClass(MatchesRegex::class)]
class MatchesRegexOperatorTest extends TestCase
{
    #[Test]
    #[DataProvider('stringBeginningWith')]
    public function it_applies_to_strings_matching_regex(string $value, string $argument): void
    {
        $operator = new MatchesRegex($value);
        $this->assertTrue($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{string, string}>
     */
    public static function stringBeginningWith(): iterable
    {
        return [
            ['/@foobar.com/',  'barbaz@foobar.com'],
        ];
    }

    #[Test]
    #[DataProvider('stringNotContaining')]
    public function it_does_not_apply_to_strings_not_matching_regex(string $value, string $argument): void
    {
        $operator = new MatchesRegex($value);
        $this->assertFalse($operator->appliesTo($argument));
    }

    /**
     * @return iterable<array{string, string}>
     */
    public static function stringNotContaining(): iterable
    {
        return [
            ['/^@foobar.com/', 'barbaz@foobar.net'],
        ];
    }

    #[Test]
    public function it_exposes_its_value(): void
    {
        $operator = new MatchesRegex('/^@foobar.com/');
        $this->assertSame('/^@foobar.com/', $operator->getValue());
    }
}
