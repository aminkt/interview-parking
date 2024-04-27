<?php

namespace Temperworks\Codechallenge\App\Query;

abstract class AQuery
{
    public static function fromArray(array $input): static
    {
        return new static(...$input);
    }
}