<?php

namespace common\generators;

final class GenerateResetToken extends AbstractGenerateString
{
    protected const PREFIX = 'reset_token_';

    protected function getAlgo(): string
    {
        return 'sha256';
    }
}