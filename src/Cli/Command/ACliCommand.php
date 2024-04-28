<?php

namespace Temperworks\Codechallenge\Cli\Command;

abstract class ACliCommand
{

    abstract public static function name(): string;
    abstract public static function description(): string;
    public static function arguments(): array
    {
        return [];
    }

    abstract public function execute(array $parameters);

    protected function ptintNewLine(string $message)
    {
        $this->print($message . "\n");
    }

    protected function print(string $message)
    {
        print($message . "\n");
        flush();
        ob_flush();
    }
}