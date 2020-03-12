<?php

declare(strict_types=1);

namespace Qandidate\Toggle;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionConditionTest extends TestCase
{
    protected $language;

    protected function setUp(): void
    {
        $this->language = new ExpressionLanguage();
    }

    /**
     * @test
     */
    public function it_should_fire_a_syntax_error_exception()
    {
        $this->expectException('Symfony\Component\ExpressionLanguage\SyntaxError');
        $condition = new ExpressionCondition('price < 5', $this->language);
        $context = new Context();

        $condition->holdsFor($context);
    }

    /**
     * @test
     */
    public function it_should_evaluate_a_correct_expression()
    {
        $condition = new ExpressionCondition('user["active"] and product["price"] >= 25', $this->language);
        $context = new Context();

        $context->set('user', [
            'active' => true,
            'tags' => ['symfony2', 'qandidate'],
        ]);

        $context->set('product', [
            'price' => 30,
        ]);

        $this->assertTrue($condition->holdsFor($context));
    }

    /**
     * @test
     */
    public function it_should_returns_false_if_the_conditions_are_not_met()
    {
        $condition = new ExpressionCondition('"bootstrap" in user["tags"]', $this->language);
        $context = new Context();

        $context->set('user', [
            'active' => true,
            'tags' => ['symfony2', 'qandidate'],
        ]);

        $this->assertFalse($condition->holdsFor($context));
    }

    /**
     * @test
     */
    public function it_should_return_false_if_the_expression_does_not_return_boolean()
    {
        $condition = new ExpressionCondition('user["tags"]', $this->language);
        $context = new Context();

        $context->set('user', [
            'active' => true,
            'tags' => ['symfony2', 'qandidate'],
        ]);

        $this->assertFalse($condition->holdsFor($context));
    }
}
