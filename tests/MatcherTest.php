<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class MatcherTest extends TestCase
{

    public function testEmptyDataGiven(): void
    {
        $this->expectException(\Integer\EmptyDataException::class);

        $this->createEmptyClassWithData([]);
    }

    public function testDataMustBeAssociativeArray(): void
    {
        $this->expectException(\Integer\DataIsNotAssociativeArrayException::class);

        $this->createEmptyClassWithData([1]);
    }

    public function testDataWithUnknownKey(): void
    {
        $this->expectException(\Integer\UnknownKeyGivenException::class);

        $this->createEmptyClassWithData(['foo' => 1]);
    }

    public function testValueWithInvalidType(): void
    {
        $this->expectException(\Integer\InvalidValueException::class);

        $usedValue = $this->createClassWithFooInt(['foo' => 'ahoj']);
    }

    public function testValueWithInvalidType2(): void
    {
        $this->expectException(\Integer\InvalidValueException::class);

        $usedValue = $this->createClassWithFooInt(['foo' => '2']);
    }

    public function testValueWithValidType(): void
    {
        $usedValue = $this->createClassWithFooInt(['foo' => 5]);
        $this->assertSame(5, $usedValue);
    }

    public function testClassWithOneUnsetNullableProperty()
    {
        $usedValue = $this->createClassWithFooAndBar(['foo' => 5]);
        $this->assertSame(5, $usedValue);
    }

    public function testClassWithOneUnsetNonNullableProperty()
    {
        $usedValue = $this->createClassWithFooAndBar(['foo' => 5]);
        $this->assertSame(5, $usedValue);
    }

    public function testClassWithInvalidDefault()
    {
        $this->expectException(\Integer\InvalidValueException::class);
        $this->createClassWithBarWithInvalidDefault();
    }

    private function createEmptyClassWithData(array $data): void
    {
        (new class($data) implements \Integer\Hydratable {
            use \Integer\DummyHydrator;

            public function __construct($data)
            {
                $this->hydrate($data, $this);
            }
        });
    }

    private function createClassWithFooInt(array $data): int
    {
        $object = (new class($data) implements \Integer\Hydratable {
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
        });

        return $object->getFoo();
    }

    private function createClassWithFooAndBar(array $data): int
    {
        $object = (new class($data) implements \Integer\Hydratable {
            use \Integer\DummyHydrator;

            /** @var int */
            private $foo;

            /** @var string|null */
            private $bar = 'a';

            public function __construct(array $data)
            {
                $this->hydrate($data, $this);
            }

            public function getFoo(): int
            {
                return $this->foo;
            }
        });

        return $object->getFoo();
    }

    private function createClassWithBarWithInvalidDefault(): int
    {
        $object = (new class(['foo' => 4]) implements \Integer\Hydratable {
            use \Integer\DummyHydrator;

            /** @var int */
            private $foo;

            /** @var string|null */
            private $bar = 5;

            public function __construct(array $data)
            {
                $this->hydrate($data, $this);
            }

            public function getFoo(): int
            {
                return $this->foo;
            }
        });

        return $object->getFoo();
    }

}
