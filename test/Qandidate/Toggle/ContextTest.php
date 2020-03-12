<?php

declare(strict_types=1);

namespace Qandidate\Toggle;

class ContextTest extends TestCase
{
    /**
     * @test
     */
    public function it_sets_a_value()
    {
        $context = new Context();
        $context->set('foo', 'bar');

        $this->assertEquals('bar', $context->get('foo'));
    }

    /**
     * @test
     * @dataProvider validValues
     */
    public function it_exposes_whether_it_has_a_value($value)
    {
        $context = new Context();
        $context->set('foo', $value);

        $this->assertTrue($context->has('foo'));
    }

    public function validValues()
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

    /**
     * @test
     */
    public function it_does_not_have_a_value_that_was_never_set()
    {
        $context = new Context();

        $this->assertFalse($context->has('foo'));
    }
}
