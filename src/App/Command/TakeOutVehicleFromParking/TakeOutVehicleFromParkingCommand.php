<?php

namespace Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking;

use Temperworks\Codechallenge\App\Command\ACommand;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;

class TakeOutVehicleFromParkingCommand extends ACommand
{
    public function __construct(
        public readonly string $parkingId,
        public readonly string $vehicleNumberPlate,
    ) {
        $errors = [];
        if (empty($this->parkingId)) {
            $errors[] = new ValidationException(
                "Parking id can not be empty.",
                'parkingId',
                $this->parkingId
            );
        }

        if (empty($this->vehicleNumberPlate)) {
            $errors[] = new ValidationException(
                "VehicleNumberPlate id can not be empty.",
                'vehicleNumberPlate',
                $this->vehicleNumberPlate
            );
        }

        !empty($errors) && throw ValidationException::fromErrors(...$errors);
    }
}