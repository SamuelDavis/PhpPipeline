# PhpPipeline
Hilarious implementation of chainable `callable` calls.

https://github.com/thephpleague/pipeline is much more _appropriate_, but this is pretty cool too.

###Example
```php
$splitKey = function (string $str) { // return an array
    return [explode('.', $str)];
};

$head = function (array $arr) { // return a string and an array
    return [array_shift($arr), $arr];
};

$upcase = function (string $str) { // return just a string
    return strtoupper($str);
};

$result = (new Pipe('foo.bar.biz.baz', true))
    ->into($splitKey)
    ->into($head)
    ->into($upcase)
    ->__invoke();

echo str_replace(['\/'], ['/'], json_encode($result, JSON_PRETTY_PRINT)) . "\n";
```

###Output
```json
FOO
```
