<?php

namespace Temperworks\Codechallenge\Cli\Command;

use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommand;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommandHandler;
use Temperworks\Codechallenge\Cli\DC;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class ParkCliCommand extends ACliCommand
{

    public static function name(): string
    {
        return "park";
    }

    public static function description(): string
    {
        return "Park car in the parking.";
    }

    public static function arguments(): array
    {
        return [
            '-p' => [
                'isRequired' => true,
                'paramName' => 'vehicleNumberPlate',
                'sample' => "-p=43rer45"
            ],
            '-t' => [
                'isRequired' => true,
                'paramName' => 'vehicleType',
                'sample' => "-p=Van"
            ],
            '-f' => [
                'isRequired' => false,
                'paramName' => 'floorNumber',
                'sample' => "-p=0"
            ]
        ];
    }

    public function execute(array $parameters)
    {
        $handler = new AddVehicleToParkingCommandHandler(
            parkingRepository: DC::parkingRepository(),
            receiptRepository: DC::receiptRepository()
        );

        try {
            $parameters['parkingId'] = DC::AppConfig()->parkingId;
            $parameters['vehicleType'] = EVehicleType::tryFrom($parameters['vehicleType']);
            $handler->execute(AddVehicleToParkingCommand::fromArray($parameters));
            $this->ptintNewLine("Welcome, please go in");
        } catch (NoParkingSpotLeftException) {
            $this->ptintNewLine("Sorry, no spaces left");
        }
    }
}