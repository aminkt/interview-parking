<?php

namespace Temperworks\Codechallenge\App\Command\TakeOutVehicleFromFloor;

use Temperworks\Codechallenge\App\Command\ACommand;
use Temperworks\Codechallenge\App\Command\ACommandHandler;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;

class TakeOutVehicleFromFloorCommandHandler extends ACommandHandler
{
    public function __construct(
        private readonly IParkingRepository $parkingRepository
    ) {}

    public function execute(TakeOutVehicleFromFloorCommand|ACommand $command)
    {
        $parkingEntity = $this->parkingRepository->getById($command->parkingId);
        $parkingEntity->takeOutVehicleFromFloor($command->vehicleType, $command->floorNumber);
        $this->parkingRepository->save($parkingEntity);
    }
}