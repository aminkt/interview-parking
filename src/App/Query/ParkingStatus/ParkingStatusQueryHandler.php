<?php

namespace Temperworks\Codechallenge\App\Query\ParkingStatus;

use Temperworks\Codechallenge\App\Query\AQuery;
use Temperworks\Codechallenge\App\Query\AQueryHandler;
use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;

class ParkingStatusQueryHandler extends AQueryHandler
{
    public function __construct(
        private readonly IParkingRepository $parkingRepo,
        private readonly IReceiptRepository $receiptRepository
    ) {}

    public function execute(ParkingStatusQuery|AQuery $query = null)
    {
        $parkingEntity = $this->parkingRepo->getById($query->parkingId);
        $receiptEntitiesGroupByFloorNumber = $this->receiptRepository->findOpenReceiptByParkingIdGroupByFloorNumber($query->parkingId);
        $floors = [];
        $totalVehiclesInParkCount = 0;
        foreach ($parkingEntity->getFloorEntities() as $floorNumber => $floorEntity) {
            $totalVehiclesInParkCount += count($receiptEntitiesGroupByFloorNumber[$floorNumber]);
            $floors[] = [
                'name' => $this->getFloorName($floorNumber),
                'totalCapacity' => $floorEntity->getTotalCapacity(),
                'remainingCapacity' => $floorEntity->getCurrentCapacity(),
                'vehiclesInParkCount' => count($receiptEntitiesGroupByFloorNumber[$floorNumber]),
                'vehicleNumberPlates' => $this->getVehicleNumberPlatesInFloor($receiptEntitiesGroupByFloorNumber[$floorNumber])
            ];
        }

        return [
            'parkingId' => $parkingEntity->getId(),
            'totalCapacity' => $parkingEntity->getTotalCapacity(),
            'remainingCapacity' => $parkingEntity->getRemainingCapacity(),
            'floorCount' => count($parkingEntity->getFloorEntities()),
            'vehiclesInParkCount' => $totalVehiclesInParkCount,
            'floors' => $floors
        ];
    }

    private function getFloorName(int $floorNumber): string
    {
        if ($floorNumber === 0) {
            return "Ground floor";
        }

        return "Floor {$floorNumber}";
    }

    /**
     * @param ReceiptEntity[] $receiptEntities
     * @return string[]
     */
    private function getVehicleNumberPlatesInFloor(array $receiptEntities): array
    {
        $result = [];
        foreach ($receiptEntities as $receiptEntity) {
            $result[] = "{$receiptEntity->getVehicleType()->name}[{$receiptEntity->getVehicleNumberPlate()}]";
        }
        return $result;
    }
}