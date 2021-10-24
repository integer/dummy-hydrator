# Dummy hydrator

Simple package for strict initialization object with an array.

Supported PHP 7.1+

## Why?

Imagine class like this
```
class DummyClass  
{

    /** @var int */
    private $foo;

    public function __construct(array $data)
    {
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getFoo(): int
    {
        return $this->foo;
    }
}
```

What if it will be initialized with array like this `['foo' => 'example-string']`?
No error in object consturction and TypeError is returned only if you use `getFoo` method.
Sad, isn't it?

Let's do it better.

Add new interface `\Integer\Hydratable` to your class. 
(Will be removed after dropping support for PHP7.1)

Add trait `Integer\DummyHydrator` and call this trait in __construct.

Full example:

```
class DummyClass  implements \Integer\Hydratable
{

    use \Integer\DummyHydrator;
    
    /** @var int */
    private $foo;

    public function __construct(array $data)
    {
        $this->hydrate($data, $this);
    }

    public function getFoo(): int
    {
        return $this->foo;
    }
}
```

## Development

Tested with PHPUnit. Use `composer` scripts to run unit tests and coverage.

```
composer tests
composer coverage
```
