# PhpPipeline
Hilarious implementation of chainable `callable` calls.

https://github.com/thephpleague/pipeline is much more _appropriate_, but this is pretty cool too. I appreciate how the state isn't obscured because the thing itself is the state.

### Example Usage
```php
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

$getResult = (new Pipe('foo bar'))
    ->into('strtoupper')
    ->into($explodeCurry)
    ->into([Remover::class, 'pop'])
    ->into([$stringHelper, 'concat'], 'fiz');

var_dump($getResult());
echo str_replace('{}', 'lambda', json_encode($getResult, JSON_PRETTY_PRINT));
```
```
~/code/pipe/example.php:33:
string(6) "BARfiz"
{
    "0": "foo bar",
    "1": [
        "strtoupper"
    ],
    "2": [
        lambda
    ],
    "3": [
        [
            "Remover",
            "pop"
        ]
    ],
    "4": [
        [
            lambda,
            "concat"
        ],
        "fiz"
    ]
}
```
