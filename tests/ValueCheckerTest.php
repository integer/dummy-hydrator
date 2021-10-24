<?php
declare(strict_types=1);

namespace Integer;

use PHPUnit\Framework\TestCase;

class ValueCheckerTest extends TestCase
{

    /**
     * @dataProvider validProvider
     */
    public function testValidValues($value, string $type)
    {
        $this->assertNull(ValueChecker::checkValue($value, $type, 'test'));
    }

    /**
     * @dataProvider invalidProvider
     */
    public function testInvalidValues($value, string $type)
    {
        $this->expectException(\Integer\InvalidValueException::class);
        ValueChecker::checkValue($value, $type, 'test');
    }

    public function validProvider()
    {
        return [
            [1, 'int'],
            ['a', 'string'],
            [null, 'null'],
            [3.14, 'float'],
            [[1,2,3], 'array'],
            [['key' => 'value'], 'array'],
            [null, 'int|null'],
            [1, 'int|null'],
            [(object) ['key' => 'value'], \stdClass::class],
            ['a', 'string|\stdClass'],
        ];
    }

    public function invalidProvider()
    {
        return [
            ['a', 'int'],
            [1, 'string'],
            [1, 'null'],
            ['a', 'int|null'],
            [1, 'string|\stdClass'],
            [[1,2], '\stdClass'],
            [['key' => 'value'], '\stdClass'],
            [(object) ['key' => 'value'], 'string'],
        ];
    }
}
