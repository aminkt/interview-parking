<?php

namespace Temperworks\Codechallenge\Cli;

use InvalidArgumentException;
use Temperworks\Codechallenge\Cli\Command\ACliCommand;

class CliApplication
{
    private array $commandMap = [];

    public function __construct()
    {
    }

    public function registerCliCommand(string $commandClassName): self
    {
        if (!is_subclass_of($commandClassName, ACliCommand::class)) {
            throw new InvalidArgumentException("$commandClassName should extend from " . ACliCommand::class);
        }
        $this->commandMap[$commandClassName::name()] = $commandClassName;
        return $this;
    }

    public function registerCliCommands(array $commandClassNames): self
    {
        foreach ($commandClassNames as $commandClassName) {
            $this->registerCliCommand($commandClassName);
        }
        return $this;
    }

    public function run(array $argv)
    {
        if (($argv[1] ?? null) == 'help') {
            $this->handleHelpCommand($argv[0]);
        } else {
            $command = $argv[1] ?? null;
            $arguments = array_slice($argv, 2, null);
            /** @var ACliCommand $cliCommand */
            $cliCommandClass = $this->commandMap[$command] ?? null;
            if ($cliCommandClass === null) {
                throw new \Exception("$command is not defined.");
            } else {
                $cliCommand = new $cliCommandClass();
                $params = $this->fetchParams($cliCommand, $arguments);
                ob_start();
                $cliCommand->execute($params);
                flush();
                ob_flush();
            }
        }
        exit(0);
    }

    private function fetchParams(ACliCommand $cliCommand, array $arguments): array
    {
        if (empty($cliCommand::arguments())) {
            return [];
        }

        $parameters = [];
        foreach ($cliCommand::arguments() as $param => $argumentsDetail) {
            foreach ($arguments as $argument) {
                $matches = [];
                if (preg_match('/' . $param . '=(\w+)/s', $argument, $matches)) {
                    $parameters[$argumentsDetail['paramName']] = $matches[1] ?? null;
                }
            }

            $isRequired = $argumentsDetail['isRequired'] ?? false;
            if ($isRequired && !array_key_exists($argumentsDetail['paramName'], $parameters)) {
                throw new InvalidArgumentException("{$param} is required.");
            }
        }

        return $parameters;
    }

    private function handleHelpCommand(string $scriptName): void
    {
        ob_start();
        print("Parking application:\n");
        print("--------------------\n");
        print("Available Commands:\n");
        $i = 1;
        foreach ($this->commandMap as $command) {
            /** @var ACliCommand $command */
            print("$i. {$command::name()}: {$command::description()} \n");
            $sampleCommand = [$scriptName, $command::name()];
            if ($command::arguments()) {
                print("\tArguments:\n");
                foreach ($command::arguments() as $alias => $details) {
                    $isRequired = $details['isRequired'] ? '[required]' : '[optional]';
                    print("\t\t{$alias}\t$isRequired {$details['paramName']}\n");
                    $sampleCommand[] = $details['sample'] ?? '';
                }
            }
            print("\t#sample: " . implode(' ', $sampleCommand) . "\n");
        }
        flush();
        ob_flush();
    }
}