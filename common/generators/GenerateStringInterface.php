<?php

namespace common\generators;

interface GenerateStringInterface
{
    public function generate(string $stringToHash): string;
}