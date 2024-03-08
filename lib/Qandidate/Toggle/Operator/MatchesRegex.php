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

/**
 * @template-extends EqualityOperator<string>
 */
class MatchesRegex extends EqualityOperator
{
    public function appliesTo(mixed $argument): bool
    {
        if (!is_string($argument)) {
            throw new \InvalidArgumentException('MatchesRegex can only be matched against strings');
        }

        return (bool) preg_match($this->value, $argument);
    }
}
