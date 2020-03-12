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

class HasIntersection extends EqualityOperator
{
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function appliesTo($argument)
    {
        return null !== $argument
            && array_intersect($argument, $this->values);
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
