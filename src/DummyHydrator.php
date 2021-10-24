<?php
declare(strict_types=1);

namespace Integer;

trait DummyHydrator
{

    /**
     * @param array $data
     * @param \Integer\Hydratable $object
     */
    private function hydrate(array $data, Hydratable $object): void
    {
        if (count($data) === 0) {
            throw new \Integer\EmptyDataException('Empty data given');
        }

        if (count(array_filter(array_keys($data), 'is_int')) > 0) {
            throw new \Integer\DataIsNotAssociativeArrayException('Only associative arrays are allowed.');
        }

        $refClass = new \ReflectionClass($object);
        $usedPropertyNames = [];
        foreach ($refClass->getProperties() as $property) {

            $propertyName = $property->getName();
            $parsedTypes = DocParser::getTypes($property->getDocComment());

            if (!array_key_exists($propertyName, $data)) {
                // Check property default
                $property->setAccessible(true);
                ValueChecker::checkValue($property->getValue($object), $parsedTypes, $propertyName);
                continue;
            }

            $value = $data[$propertyName];
            $usedPropertyNames[$propertyName] = 'found';

            ValueChecker::checkValue($value, $parsedTypes, $propertyName);

            $property->setAccessible(true);
            $property->setValue($this, $value);
        }

        $keys = array_keys(array_diff_key($data, $usedPropertyNames));
        if (count($keys) !== 0) {
            throw new \Integer\UnknownKeyGivenException(
                sprintf('Keys "%s" has not relevant property defined.', implode(', ', $keys))
            );
        }
    }

}
// todo dopsat README.md
