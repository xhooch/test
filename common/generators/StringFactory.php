<?php

namespace common\generators;

class StringFactory implements FactoryInterface
{
    private static GenerateStringInterface|null $instance;

    public static function create(string $className): GenerateStringInterface
    {
        self::$instance = new $className;
        return self::$instance;
    }

    public static function getInstance(): GenerateStringInterface|null
    {
        return self::$instance;
    }
}