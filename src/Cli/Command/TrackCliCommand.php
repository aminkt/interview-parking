<?php

namespace Temperworks\Codechallenge\Cli\Command;

use Temperworks\Codechallenge\App\Query\GetVehicleReceiptList\GetVehicleReceiptListQuery;
use Temperworks\Codechallenge\App\Query\GetVehicleReceiptList\GetVehicleReceiptListQueryHandler;
use Temperworks\Codechallenge\Cli\DC;

class TrackCliCommand extends ACliCommand
{

    public static function name(): string
    {
        return "track";
    }

    public static function description(): string
    {
        return "Track parking history of a vehicle.";
    }

    public static function arguments(): array
    {
        return [
            '-p' => [
                'isRequired' => true,
                'paramName' => 'vehicleNumberPlate',
                'sample' => "-p=43rer45"
            ]
        ];
    }

    public function execute(array $parameters)
    {
        $handler = new GetVehicleReceiptListQueryHandler(DC::receiptRepository());

        $handler->execute(GetVehicleReceiptListQuery::fromArray($parameters));
        $this->ptintNewLine("Good bye!");
    }
}