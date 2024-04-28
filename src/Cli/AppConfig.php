<?php

namespace Temperworks\Codechallenge\Cli;

class AppConfig
{
    public ?string $parkingId;

    public function __construct()
    {
        $this->parkingId = getenv("PARKING_ID") ?: null;
    }
}