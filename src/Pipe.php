<?php

namespace Pipe;

use ArrayObject;

class Pipe extends ArrayObject
{
    /**
     * Pipe constructor.
     * @param mixed $input
     */
    public function __construct($input = null)
    {
        $this[0] = $input;
    }

    public function into(callable $function, ...$args): Pipe
    {
        $this[] = func_get_args();
        return $this;
    }

    public function __invoke($input = null)
    {
        return array_reduce(array_slice((array)$this, 1), function ($result, array $args) {
            $cb = array_shift($args);
            array_unshift($args, $result);
            return $cb(...$args);
        }, $input ?: $this[0]);
    }
}
