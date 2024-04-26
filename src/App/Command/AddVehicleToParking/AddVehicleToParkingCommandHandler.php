<?php

namespace Temperworks\Codechallenge\App\Command\AddVehicleToParking;

use Temperworks\Codechallenge\App\Command\ACommand;
use Temperworks\Codechallenge\App\Command\ACommandHandler;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;

class AddVehicleToParkingCommandHandler extends ACommandHandler
{
    public function __construct(
        private readonly IParkingRepository $parkingRepository
    ) {}

    public function execute(AddVehicleToParkingCommand|ACommand $command)
    {
        $parkingEntity = $this->parkingRepository->getById($command->parkingId);
        if ($command->floorNumber) {
            $parkingEntity->parkVehicleInFloor($command->vehicleType, $command->floorNumber);
        } else {
            $parkingEntity->parkVehicle($command->vehicleType);
        }
        $this->parkingRepository->save($parkingEntity);
    }
}