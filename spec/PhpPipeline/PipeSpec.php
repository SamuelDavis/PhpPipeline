<?php

namespace spec\PhpPipeline;

use PhpPipeline\Pipe;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PipeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Pipe::class);
    }

    public function it_frontloads_input()
    {
        $value = 'some input';
        $this->beConstructedWith($value);
        $this->shouldHaveKeyWithValue(0, $value);
    }

    public function it_appends_callables()
    {
        $next = 'strtoupper';
        $this->into($next)->shouldHaveKeyWithValue(1, [$next]);
    }

    public function it_curries_callable_arguments()
    {
        $next = 'strtoupper';
        $with = 'foo';
        $this->into($next, $with)->shouldHaveKeyWithValue(1, [$next, $with]);
    }

    public function it_returns_input()
    {
        $input = 'foo';
        $this->beConstructedWith($input);
        $this->__invoke()->shouldReturn($input);
    }

    public function it_overrides_input()
    {
        $initialInput = 'foo';
        $this->beConstructedWith($initialInput);
        $invokingInput = 'bar';
        $this->__invoke($invokingInput)->shouldReturn($invokingInput);
    }

    public function it_transforms_input()
    {
        $input = 'foo';
        $next = 'strtoupper';
        $expected = $next($input);
        $this->into($next)->__invoke($input)->shouldReturn($expected);
    }

    public function it_applies_all_callables()
    {
        $this
            ->into('strtoupper')
            ->into('substr', -3)
            ->__invoke('foo bar')
            ->shouldReturn('BAR');
    }
}
