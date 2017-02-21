<?php

use PhpPipeline\Pipe;

require_once __DIR__ . '/vendor/autoload.php';

class Remover
{
    public static function pop(array $arr = [])
    {
        return array_pop($arr);
    }
}

$stringHelper = new class
{
    public function concat(string $initial, string $addition): string
    {
        return $initial . $addition;
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
    ->into([Remover::class, 'pop'])
    ->into([$stringHelper, 'concat'], 'fiz');

var_dump($getResult());
echo "========\n" . json_encode($getResult, JSON_PRETTY_PRINT);
