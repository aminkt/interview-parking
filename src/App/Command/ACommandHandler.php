<?php

namespace Temperworks\Codechallenge\App\Command;

abstract class ACommandHandler
{
    abstract public function execute(ACommand $command);
}