<?php

use PhpPipeline\Pipe;

require_once __DIR__ . '/vendor/autoload.php';

class Arr
{
    public static function pop(array $arr = [])
    {
        return array_pop($arr);
    }
}

$explodeCurry = function (string $string) {
    return explode(' ', $string);
};

$getResult = (new Pipe('foo bar'))
    ->into('strtoupper')
    ->into($explodeCurry)
    ->into([Arr::class, 'pop']);

var_dump($getResult());
