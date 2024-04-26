<?php

namespace Temperworks\Codechallenge\App\Command;

class ACommand
{
    public static function fromArray(array $input): static
    {
        return new static(...$input);
    }
}