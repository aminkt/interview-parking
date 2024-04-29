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

        $items = $handler->execute(GetVehicleReceiptListQuery::fromArray($parameters));
        $headers = ['receiptId', 'parkingId', 'floor', 'isInPark', 'parkTime', 'parkAt', 'takeOutAt'];
        $mask = "|%10s |%10s |%10s |%10s |%20s |%30s |%30s |\n";
        printf($mask, ...$headers);
        foreach ($items as $item) {
            printf($mask, ...array_values($item));
        }
    }
}