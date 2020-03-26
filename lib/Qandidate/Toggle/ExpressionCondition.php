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

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * A condition written as a symfony language expression that gets evaluated against the
 * full context, allowing access to several keys of the context in a single condition.
 */
class ExpressionCondition extends Condition
{
    /**
     * @var string
     */
    protected $expression;

    /**
     * @var ExpressionLanguage
     */
    protected $language;

    public function __construct(string $expression, ExpressionLanguage $language)
    {
        $this->expression = $expression;
        $this->language = $language;
    }

    /**
     * {@inheritdoc}
     */
    public function holdsFor(Context $context): bool
    {
        return true === $this->language->evaluate($this->expression, $context->toArray());
    }
}
