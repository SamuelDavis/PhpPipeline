# PhpPipeline
Hilarious implementation of chainable `callable` calls.

###Example
```php
$startingContainer = [
    'foo' => [
        'thud' => true,
        'bar' => [],
    ],
];

class Arr
{
    public static function splitKey(string $key, string $separator = '.')
    {
        return explode($separator, $key);
    }

    public static function nest(array $keys, $value)
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

    public static function merge()
    {
        return array_merge_recursive(...array_map(function ($thing) {
            return is_array($thing) ? $thing : [$thing];
        }, array_reverse(func_get_args())));
    }

    public static function set(string $key, $value, array $container)
    {
        return (new Pipe())
            ->into([static::class, 'splitKey'])
            ->into([static::class, 'nest'], $value)
            ->into([static::class, 'merge'], $container)
            ->__invoke($key);
    }
}

$nestingHyphensPipeline = (new Pipe())
    ->into([Arr::class, 'splitKey'], '-')
    ->into([Arr::class, 'nest'], 'Foobar');

$mergingPipeline = (new Pipe())
    ->into([Arr::class, 'merge'], $startingContainer);

$endingContainer = (new Pipe())
    ->into($nestingHyphensPipeline)
    ->into($mergingPipeline)
    ->__invoke('foo-bar-biz-baz');

$result = [
    'starting' => $startingContainer,
    'ending' => $endingContainer,
    'Arr::set' => Arr::set('foo.bar.biz.baz', 'Foobar', $startingContainer),
];

echo str_replace(['\/'], ['/'], json_encode($result, JSON_PRETTY_PRINT)) . "\n";
```

###Output
```json
{
    "starting": {
        "foo": {
            "thud": true,
            "bar": []
        }
    },
    "ending": {
        "foo": {
            "thud": true,
            "bar": {
                "biz": {
                    "baz": "Foobar"
                }
            }
        }
    },
    "Arr::set": {
        "foo": {
            "thud": true,
            "bar": {
                "biz": {
                    "baz": "Foobar"
                }
            }
        }
    }
}
```
