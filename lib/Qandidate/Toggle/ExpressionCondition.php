<?php

namespace Qandidate\Toggle;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * A condition written as a symfony language expression that gets evaluated against the
 * full context, allowing access to several keys of the context in a single condition
 *
 * @package Qandidate\Toggle
 */
class ExpressionCondition extends Condition
{
    protected $expression;
    protected $language;

    /**
     * @param string $expression The expression to ve evaluated
     */
    function __construct($expression)
    {
        $this->expression = $expression;
        $this->language   = new ExpressionLanguage();
    }


    /**
     * @inheritdoc
     */
    public function holdsFor(Context $context)
    {
        return $this->language->evaluate($this->expression, $context->toArray());
    }

} 