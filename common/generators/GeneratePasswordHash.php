<?php

namespace common\generators;

final class GeneratePasswordHash extends AbstractGenerateString
{
    protected const PREFIX = 'pass_hash_';

    protected function getAlgo(): string
    {
        return 'sha1';
    }
}