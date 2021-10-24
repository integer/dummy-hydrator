<?php
declare(strict_types=1);

namespace Integer;

class ValueChecker
{

    public static function checkValue($value, string $type, string $propertyName): void
    {
        $types = explode('|', $type);
        $valid = false;
        foreach ($types as $t) {
            if ($t === 'int') {
                $valid = ($valid || is_int($value));
            } elseif (strtolower($t) === 'null') {
                $valid = ($valid || is_null($value));
            } elseif ($t === 'float') {
                $valid = ($valid || is_float($value));
            } elseif ($t === 'string') {
                $valid = ($valid || is_string($value));
            } elseif ($t === 'array') {
                $valid = ($valid || is_array($value));
            } else {
                $valid = ($valid || is_a($value, $t));
            }
        }

        if (!$valid) {
            $valueAsString = (is_scalar($value) ? $value : (
                is_array($value) ? var_export($value, true) : 'object ' . gettype($value)
            ));
            throw new \Integer\InvalidValueException(
                sprintf(
                    'Invalid value. Annotation for "%s" expects "%s" but "%s" given.',
                    $propertyName,
                    $type,
                    $valueAsString
                )
            );
        }
    }

}
