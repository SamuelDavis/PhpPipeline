<?php

use PhpPipeline\Pipe;

require_once __DIR__ . '/vendor/autoload.php';

class Arr
{
    public static function nest(array $keys, $value): array
    {
        $valueKey = array_pop($keys);
        $container = [];
        $depth = &$container;
        foreach ($keys as $key) {
            $depth[$key] = [];
            $depth = &$depth[$key];
        }
        $depth[$valueKey] = $value;
        return $container;
    }
}

$splitKey = function (string $key, string $separator = '.'): array {
    return explode($separator, $key);
};

$startingContainer = [
    'foo' => [
        'thud' => true,
        'bar' => [],
    ],
];

$nestingHyphensPipeline = (new Pipe)
    ->into($splitKey, '-')
    ->into([Arr::class, 'nest'], 'Foobar');

$mergingPipeline = (new Pipe)
    ->into(function (): array {
        return array_merge_recursive(...array_map(function ($thing): array {
            return is_array($thing) ? $thing : [$thing];
        }, array_reverse(func_get_args())));
    }, $startingContainer);

$endingContainer = (new Pipe('FOO-BAR-BIZ-BAZ'))
    ->into('strtolower')
    ->into($nestingHyphensPipeline)
    ->into($mergingPipeline)
    ->__invoke();

$result = [
    'starting' => $startingContainer,
    'ending' => $endingContainer,
];

echo str_replace(['\/'], ['/'], json_encode($result, JSON_PRETTY_PRINT)) . "\n";
