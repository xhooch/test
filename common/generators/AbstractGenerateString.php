<?php

namespace common\generators;

use Stringable;

abstract class AbstractGenerateString implements GenerateStringInterface, Stringable
{
    protected const PREFIX = '';

    public function __toString()
    {
        return (string)$this;
    }

    public function generate(string $stringToHash): string
    {
        return hash($this->getAlgo(), static::PREFIX . $stringToHash);
    }

    abstract protected function getAlgo(): string;
}