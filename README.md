# PhpPipeline
Hilarious implementation of chainable `callable` calls.

https://github.com/thephpleague/pipeline is much more _appropriate_, but this is pretty cool too. I appreciate how the state isn't obscured because the thing itself is the state.

### Example Usage
```php
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
```
```
~/code/PhpPipeline/example.php:24:
string(3) "BAR"
```
