<?php

namespace Axytos\ECommerce\DependencyInjection;

class MissingConstructorParameterTypeException extends \Exception
{
    /** @param \ReflectionClass<object> $reflectionClass */
    public function __construct(
        \ReflectionClass $reflectionClass,
        \ReflectionParameter $constructorParameter
    ) {
        $className = $reflectionClass->getName();
        $parameterName = $constructorParameter->getName();

        parent::__construct("Missing type for constructor parameter {$parameterName} of class {$className}");
    }
}
