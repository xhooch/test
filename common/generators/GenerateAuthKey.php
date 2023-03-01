<?php

namespace common\generators;

final class GenerateAuthKey extends AbstractGenerateString
{
    protected const PREFIX = 'auth_key_';

    protected function getAlgo(): string
    {
        return 'md5';
    }
}