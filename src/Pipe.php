<?php

namespace Pipe;

use ArrayObject;

class Pipe extends ArrayObject
{
    const RETURN_MANY = 0;

    private $options;

    /**
     * Pipe constructor.
     * @param mixed $input
     * @param array $options
     */
    public function __construct($input = null, array $options = [])
    {
        $this[0] = $input;
        $this->options = $options;
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
            if (in_array(static::RETURN_MANY, $this->options)) {
                $result = is_array($result) ? $result : [$result];
                $args = array_merge($result, $args);
            } else {
                array_unshift($args, $result);
            }
            return call_user_func_array($cb, $args);
        }, $input ?: $this[0]);
    }
}
