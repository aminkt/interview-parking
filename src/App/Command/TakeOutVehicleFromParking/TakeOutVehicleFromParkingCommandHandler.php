<?php

namespace Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking;

use DateTimeImmutable;
use Temperworks\Codechallenge\App\Command\ACommand;
use Temperworks\Codechallenge\App\Command\ACommandHandler;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;

class TakeOutVehicleFromParkingCommandHandler extends ACommandHandler
{
    public function __construct(
        private readonly IParkingRepository $parkingRepository,
        private readonly IReceiptRepository $receiptRepository
    ) {}

    public function execute(TakeOutVehicleFromParkingCommand|ACommand $command)
    {
        // This lines should be atomic for strong consistency.
        $receiptEntity = $this->receiptRepository->findOpenReceiptByParkingIdAndVehicleNumberPlate($command->parkingId, $command->vehicleNumberPlate);
        $receiptEntity->setTakeOutAt(new DateTimeImmutable("now"));
        $this->receiptRepository->save($receiptEntity);

        $parkingEntity = $this->parkingRepository->getById($receiptEntity->getParkingId());
        $parkingEntity->takeOutVehicleFromFloor($receiptEntity->getVehicleType(), $receiptEntity->getFloorNumber());
        $this->parkingRepository->save($parkingEntity);
    }
}