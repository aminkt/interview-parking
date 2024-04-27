<?php

namespace Temperworks\Codechallenge\Infra\Repository\InMemory;

use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;
use Temperworks\Codechallenge\Domain\Exception\EntityNotFoundException;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;

class ReceiptInMemoryRepository extends AInMemoryRepo implements IReceiptRepository
{
    public static function getAggregateEntityName(): string
    {
        return ReceiptEntity::class;
    }

    public function findAllReceiptByVehicleNumberPlate(string $numberPlate): array
    {
        $result = [];
        foreach ($this->getAll() as $entity) {
            /** @var ReceiptEntity $entity */
            if ($entity->getVehicleNumberPlate() === $numberPlate) {
                $result[] = $entity;
            }
        }
        return $result;
    }

    public function findOpenReceiptByParkingIdAndVehicleNumberPlate(string $parkingId, string $numberPlate): ReceiptEntity
    {
        foreach ($this->getAll() as $entity) {
            /** @var ReceiptEntity $entity */
            $condition = $entity->getParkingId() === $parkingId
                && $entity->getVehicleNumberPlate() === $numberPlate
                && $entity->getTakeOutAt() === null;

            if ($condition) {
                return $entity;
            }
        }
        throw new EntityNotFoundException("Can not find open parking receipt for parking {$parkingId} and numberPlate $numberPlate}");
    }
}