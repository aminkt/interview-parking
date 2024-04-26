<?php

namespace Temperworks\Codechallenge\App\Command\AddVehicleToParking;

use Temperworks\Codechallenge\App\Command\ACommand;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class AddVehicleToParkingCommand extends ACommand
{
    public function __construct(
        public readonly string $parkingId,
        public readonly EVehicleType $vehicleType,
        public readonly ?int $floorNumber = null
    ) {}
}