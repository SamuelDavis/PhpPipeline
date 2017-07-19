<?php

namespace PhpPipeline\Example;

use PhpPipeline\Pipe;

require_once __DIR__ . '/vendor/autoload.php';

interface PipelineSugar
{
    public function __get(string $name): callable;
}

class Remover
{
    const pop = [self::class, 'pop'];

    public static function pop(array $arr = [])
    {
        return array_pop($arr);
    }

    public function __get(string $name): callable
    {
        return [static::class, $name];
    }
}

$stringHelper = new class implements PipelineSugar
{
    public function concat(string $initial, string $addition): string
    {
        return $initial . $addition;
    }

    public function __get(string $name): callable
    {
        return [$this, $name];
    }
};

$explodeCurry = function (string $string) {
    return explode(' ', $string);
};

$explodingPipeline = (new Pipe)
    ->into('strtoupper')
    ->into($explodeCurry);

$getResult = (new Pipe('foo bar'))
    ->into($explodingPipeline)
    ->into(Remover::pop)
    ->into($stringHelper->concat, 'fiz');

var_dump($getResult());
echo "========\n" . json_encode($getResult, JSON_PRETTY_PRINT);
