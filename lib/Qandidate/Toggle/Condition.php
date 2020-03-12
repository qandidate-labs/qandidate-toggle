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

/**
 * For a condition it can be checked if it holds for a given context.
 */
abstract class Condition
{
    /**
     * @return bool True, if the condition holds for the given context
     */
    abstract public function holdsFor(Context $context);
}
