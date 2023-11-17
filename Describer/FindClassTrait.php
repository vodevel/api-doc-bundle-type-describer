<?php

declare(strict_types=1);

namespace Vodevel\ApiDocBundleTypeDescriber\Describer;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionType;
use ReflectionUnionType;

trait FindClassTrait
{
    private function findClassFromReturn(ReflectionMethod $reflectionMethod, $class): ?ClassInfo
    {
        return $this->findClass($reflectionMethod, $this->getReturnTypes($reflectionMethod), $class);
    }

    private function findClassFromParams(ReflectionMethod $reflectionMethod, $class): ?ClassInfo
    {
        return $this->findClass($reflectionMethod, $this->getParamTypes($reflectionMethod), $class);
    }

    /**
     * @param array<ReflectionType> $types
     */
    private function findClass(ReflectionMethod $reflectionMethod, array $types, string $class): ?ClassInfo
    {
        if ($this->searchAttribute($reflectionMethod->getAttributes(), $class)) {
            return null;
        }

        foreach ($types as $type) {
            if (!$type || $type->isBuiltin() !== false) {
                continue;
            }
            $reflectClass = new ReflectionClass($type->getName());
            if ($attribute = $this->searchAttribute($reflectClass->getAttributes(), $class)) {
                return new ClassInfo(
                    $reflectClass->getName(),
                    $attribute->getArguments(),
                );
            }
        }

        return null;
    }

    private function searchAttribute(array $attributes, string $class): ?ReflectionAttribute
    {
        foreach ($attributes as $attribute) {
            if (
                $attribute->getName() === $class
                || is_subclass_of($attribute->getName(), $class)
            ) {
                return $attribute;
            }
        }

        return null;
    }

    private function getReturnTypes(ReflectionMethod $reflectionMethod): array
    {
        return $this->getAllTypes($reflectionMethod->getReturnType());
    }

    private function getParamTypes(ReflectionMethod $reflectionMethod): array
    {
        $types = [];
        foreach ($reflectionMethod->getParameters() as $param) {
            $types = array_merge($types, $this->getAllTypes($param->getType()));
        }

        return $types;
    }

    /**
     * @return array<ReflectionType>
     */
    private function getAllTypes($type): array
    {
        if ($type instanceof ReflectionUnionType) {
            return $type->getTypes();
        }

        return [$type];
    }
}
