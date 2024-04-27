<?php

namespace Temperworks\Codechallenge\App\Command;

abstract class ACommand
{
    /**
     * Create command from array input
     * @param array $input
     * @return static
     */
    public static function fromArray(array $input): static
    {
        return new static(...$input);
    }
}