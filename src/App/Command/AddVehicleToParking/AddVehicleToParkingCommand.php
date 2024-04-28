<?php

namespace Temperworks\Codechallenge\App\Command\AddVehicleToParking;

use Temperworks\Codechallenge\App\Command\ACommand;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class AddVehicleToParkingCommand extends ACommand
{
    public function __construct(
        public readonly string $parkingId,
        public readonly string $vehicleNumberPlate,
        public readonly EVehicleType $vehicleType,
        public readonly ?int $floorNumber = null
    ) {
        // By This validation, we are stick to business requirements and also make or domain model extendable.
        // Keep in mind, This validation is just related to user input validations. Logical validations will implement
        // in Domain layer or commandHandler itself. because there you will be access data stored in the system.
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
                "VehicleNumberPlate can not be empty.",
                'vehicleNumberPlate',
                $this->vehicleNumberPlate
            );
        }
        if ($this->floorNumber < 0 || $this->floorNumber >= 3) {
            $errors[] = new ValidationException(
                "Invalid floor number is provided.",
                'floorNumber',
                $this->floorNumber
            );
        }

        !empty($errors) && throw ValidationException::fromErrors(...$errors);
    }
}