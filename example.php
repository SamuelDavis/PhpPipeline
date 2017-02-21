<?php

use PhpPipeline\Pipe;

require_once __DIR__ . '/vendor/autoload.php';

$splitKey = function (string $str) { // return an array
    return [explode('.', $str)];
};

$head = function (array $arr) { // return a string and an array
    return [array_shift($arr), $arr];
};

$upcase = function (string $str) { // return just a string
    return strtoupper($str);
};

$result = (new Pipe('foo.bar.biz.baz', [Pipe::RETURN_MANY]))
    ->into($splitKey)
    ->into($head)
    ->into($upcase)
    ->__invoke();

echo str_replace(['\/'], ['/'], json_encode($result, JSON_PRETTY_PRINT)) . "\n";
