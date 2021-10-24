<?php

namespace Integer;

use PHPUnit\Framework\TestCase;

class DocParserTest extends TestCase
{

    public function testNoAnnotation()
    {
        $this->expectException(NoAnnotationException::class);
        DocParser::getTypes('/** no annotation */');
    }

    public function testSimpleAnnotation()
    {
        $parsed = DocParser::getTypes('/** @var int */');
        $this->assertSame('int', $parsed);
    }

    public function testMoreAnnotations()
    {
        $parsed = DocParser::getTypes('/** @see to the sun @var int */');
        $this->assertSame('int', $parsed);
    }
}
