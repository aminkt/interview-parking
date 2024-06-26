<?php

namespace Temperworks\Codechallenge\Infra\Repository\InMemory;

use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;
use Temperworks\Codechallenge\Domain\Exception\EntityNotFoundException;

trait TReceiptRepository
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
            if ($entity->getParkingId() === $parkingId && $entity->getVehicleNumberPlate() === $numberPlate && $entity->getTakeOutAt() == null) {
                return $entity;
            }
        }
        throw new EntityNotFoundException("Can not find open parking receipt for parking {$parkingId} and numberPlate $numberPlate}");
    }

    public function findOpenReceiptByParkingIdGroupByFloorNumber(string $parkingId): array
    {
        $result = [];
        foreach ($this->getAll() as $entity) {
            /** @var ReceiptEntity $entity */
            if ($entity->getParkingId() === $parkingId && $entity->getTakeOutAt() == null) {
                $result[$entity->getFloorNumber()][] = $entity;
            }
        }
        return $result;
    }
}