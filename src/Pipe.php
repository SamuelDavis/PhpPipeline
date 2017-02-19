<?php

namespace Pipe;

use ArrayObject;

class Pipe extends ArrayObject
{
    public function into(callable $function, ...$args): Pipe
    {
        $this[] = func_get_args();
        return $this;
    }

    public function __invoke($input = null)
    {
        return array_reduce((array)$this, function ($result, array $args) {
            $cb = array_shift($args);
            array_unshift($args, $result);
            return call_user_func_array($cb, $args);
        }, $input);
    }
}
