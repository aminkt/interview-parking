<?php

namespace Temperworks\Codechallenge\Cli\Command;

use Temperworks\Codechallenge\App\Query\GetVehicleTypesList\GetVehicleTypesListQuery;
use Temperworks\Codechallenge\App\Query\GetVehicleTypesList\GetVehicleTypesListQueryHandler;

class VehicleTypesCliCommand extends ACliCommand
{
    public static function name(): string
    {
        return "vehicle-types";
    }

    public static function description(): string
    {
        return "Get list of available vehicle types that can be handled by the application.";
    }

    public function execute(array $parameters)
    {
        $handler = new GetVehicleTypesListQueryHandler();
        $items = $handler->execute(new GetVehicleTypesListQuery());
        foreach ($items as $item) {
            $this->ptintNewLine("-{$item['name']}: Take {$item['requiredSpace']} space");
        }
    }
}