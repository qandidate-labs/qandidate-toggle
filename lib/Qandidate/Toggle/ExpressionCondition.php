<?php

declare(strict_types=1);

namespace Qandidate\Toggle;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * A condition written as a symfony language expression that gets evaluated against the
 * full context, allowing access to several keys of the context in a single condition.
 */
class ExpressionCondition extends Condition
{
    protected $expression;
    protected $language;

    /**
     * @param string             $expression The expression to be evaluated
     * @param ExpressionLanguage $language   The instance of the Expression Language
     */
    public function __construct($expression, ExpressionLanguage $language)
    {
        $this->expression = $expression;
        $this->language = $language;
    }

    /**
     * {@inheritdoc}
     */
    public function holdsFor(Context $context)
    {
        return true === $this->language->evaluate($this->expression, $context->toArray());
    }
}
