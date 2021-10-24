<?php
declare(strict_types=1);

namespace Integer;

class DocParser
{

    public static function getTypes(string $comment): string
    {
        $parts = explode('@', $comment);
        foreach ($parts as $part) {
            [$annotationName, $type] = explode(' ', $part);
            if ($annotationName === 'var') {
                return $type;
            }
        }

        throw new \Integer\NoAnnotationException(sprintf('No var annotation in doc comment "%s"', $comment));
    }
}
