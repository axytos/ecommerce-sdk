<?php declare(strict_types=1);

namespace Axytos\ECommerce\DependencyInjection;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

class ClassContainerEntry implements ContainerEntryInterface
{
    /** @var class-string $className */
    private string $className;

    /** @param class-string $className */
    public function __construct(string $className)
    {
        $this->className = $className;
    }
    /** 
     * @return object|null 
     */
    public function getInstance(Container $container)
    {
        return self::createClassInstance($this->className, $container);
    }

    /**
     * @param class-string $className
     * @return object
     */
    private static function createClassInstance(string $className, Container $container)
    {
        $reflectionClass = new ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor))
        {
            return $reflectionClass->newInstance();
        }

        $constructorArguments = self::createConstructorArguments($constructor, $container);
        
        return $reflectionClass->newInstanceArgs($constructorArguments); 
    }

    /** @return array */
    private static function createConstructorArguments(ReflectionMethod $constructor, Container $container)
    {
        $constructorArguments = [];
        $constructorParameters = $constructor->getParameters();

        foreach ($constructorParameters as $constructorParameter)
        {
            $type = $constructorParameter->getType();
            if ($type instanceof ReflectionNamedType)
            {
                /** @phpstan-ignore-next-line */
                $constructorArgument = $container->get($type->getName());
                array_push($constructorArguments, $constructorArgument);
            }
            else
            {
                throw new MissingConstructorParameterTypeException($constructor->getDeclaringClass(), $constructorParameter);
            }
        }

        return $constructorArguments;
    }
}