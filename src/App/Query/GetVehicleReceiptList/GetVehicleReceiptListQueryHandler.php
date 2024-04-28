<?php

namespace Temperworks\Codechallenge\App\Query\GetVehicleReceiptList;


use Temperworks\Codechallenge\App\Query\AQuery;
use Temperworks\Codechallenge\App\Query\AQueryHandler;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;

class GetVehicleReceiptListQueryHandler extends AQueryHandler
{

    public function __construct(private readonly IReceiptRepository $receiptRepository)
    {
    }

    public function execute(GetVehicleReceiptListQuery|AQuery $query)
    {
        is_null($query) && throw new ValidationException("Query is required!");
        $entities = $this->receiptRepository->findAllReceiptByVehicleNumberPlate($query->vehicleNumberPlate);
        $response = [];
        foreach ($entities as $entity) {
            $response[] = [
                'receiptId' => $entity->getId(),
                'parkingId' => $entity->getParkingId(),
                'floor' => $entity->getFloorNumber(),
                'isInPark' => $entity->getTakeOutAt() ? 'No' : 'Yes',
                'parkTime' => $entity->calculateParkTime()->i . " minutes",
                'parkAt' => $entity->getParkAt()->format("Y-m-d H:i"),
                'takeOutAt' => $entity->getTakeOutAt()?->format("Y-m-d H:i"),
            ];
        }
        return $response;
    }
}