<?php

namespace Temperworks\Codechallenge\Cli\Command;

use Temperworks\Codechallenge\App\Query\ParkingStatus\ParkingStatusQuery;
use Temperworks\Codechallenge\App\Query\ParkingStatus\ParkingStatusQueryHandler;
use Temperworks\Codechallenge\Cli\DC;

class StatusCliCommand extends ACliCommand
{

    public static function name(): string
    {
        return "status";
    }

    public static function description(): string
    {
        return "See the Parking status.";
    }

    public function execute(array $parameters)
    {
        $handler = new ParkingStatusQueryHandler(DC::parkingRepository(), DC::receiptRepository());

        $response = $handler->execute(new ParkingStatusQuery(DC::AppConfig()->parkingId));
        $this->ptintNewLine("parkingId: {$response['parkingId']}");
        $this->ptintNewLine("totalCapacity: {$response['totalCapacity']}");
        $this->ptintNewLine("remainingCapacity: {$response['remainingCapacity']}");
        $this->ptintNewLine("floorCount: {$response['floorCount']}");
        $this->ptintNewLine("vehiclesInParkCount: {$response['vehiclesInParkCount']}");
        $this->ptintNewLine("\nfloors: ");
        $this->drawHorizontalLine();

        $mask = "|%15s |%15s |%20s |%20s |%30s |\n";
        printf($mask, ...['name', 'totalCapacity', 'remainingCapacity', 'vehiclesInParkCount', 'vehicleNumberPlates']);
        foreach ($response['floors'] as $floor) {
            $numberPlates = array_pop($floor);
            $values = array_values($floor);
            $values[] = implode(', ', $numberPlates);
            printf($mask, ...$values);
        }
    }
}