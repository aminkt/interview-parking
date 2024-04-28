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
        var_dump($response);
    }
}