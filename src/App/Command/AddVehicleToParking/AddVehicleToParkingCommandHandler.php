<?php

namespace Temperworks\Codechallenge\App\Command\AddVehicleToParking;

use Temperworks\Codechallenge\App\Command\ACommand;
use Temperworks\Codechallenge\App\Command\ACommandHandler;
use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;
use Temperworks\Codechallenge\Domain\Exception\EntityNotFoundException;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;

class AddVehicleToParkingCommandHandler extends ACommandHandler
{
    public function __construct(
        private readonly IParkingRepository $parkingRepository,
        private readonly IReceiptRepository $receiptRepository,
    )
    {
    }

    public function execute(AddVehicleToParkingCommand|ACommand $command)
    {
        $this->checkVehicleIsNotParked($command->parkingId, $command->vehicleNumberPlate);

        $parkingEntity = $this->parkingRepository->getById($command->parkingId);
        $floorNumber = $command->floorNumber;
        if ($floorNumber) {
            $parkingEntity->parkVehicleInFloor($command->vehicleType, $command->floorNumber);
        } else {
            $floorNumber = $parkingEntity->parkVehicle($command->vehicleType);
        }

        $receiptEntity = new ReceiptEntity(
            null,
            $command->vehicleNumberPlate,
            $command->vehicleType,
            $parkingEntity->getId(),
            $floorNumber,
            new \DateTimeImmutable("now")
        );

        // This style of coding is not safe. concurrent request may lead us to serious inconsistency issue.
        // If Consistency is very important to us, we can use RDBMS transactions. Also, there is conditions in Reservation situation which is called Fantom issue.
        // But Because this application won't run on an environment which concurrency happens, it is ok for now.
        $this->receiptRepository->save($receiptEntity);
        $this->parkingRepository->save($parkingEntity);
    }

    private function checkVehicleIsNotParked(string $parkingId, string $numberPlate)
    {
        try {
            $this->receiptRepository->findOpenReceiptByParkingIdAndVehicleNumberPlate($parkingId, $numberPlate);
            throw new ValidationException("Vehicle is parked!", 'vehicleNumberPlate', $numberPlate);
        } catch (EntityNotFoundException) {

        }
    }
}