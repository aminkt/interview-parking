<?php

namespace Temperworks\Codechallenge\Cli\Command;

use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking\TakeOutVehicleFromParkingCommand;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking\TakeOutVehicleFromParkingCommandHandler;
use Temperworks\Codechallenge\Cli\DC;

class TakeOutCliCommand extends ACliCommand
{

    public static function name(): string
    {
        return "take-out";
    }

    public static function description(): string
    {
        return "Take out car from the parking.";
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
        $handler = new TakeOutVehicleFromParkingCommandHandler(
            parkingRepository: DC::parkingRepository(),
            receiptRepository: DC::receiptRepository()
        );

        $parameters['parkingId'] = DC::AppConfig()->parkingId;
        $handler->execute(TakeOutVehicleFromParkingCommand::fromArray($parameters));
        $this->ptintNewLine("Good bye!");
    }
}