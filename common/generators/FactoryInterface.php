<?php

namespace common\generators;

interface FactoryInterface
{
    public static function create(string $className): GenerateStringInterface;

    public static function getInstance(): GenerateStringInterface|null;
}