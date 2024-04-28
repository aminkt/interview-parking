<?php

namespace Temperworks\Codechallenge\Domain\Repository;

use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;

/**
 * @method ReceiptEntity[] getByIds(string ...$ids)
 * @method ReceiptEntity getById(string $id)
 * @method ReceiptEntity save(ReceiptEntity $entities)
 * @method ReceiptEntity[] saveAll(ReceiptEntity ...$entities)
 */
interface IReceiptRepository extends IRepository
{
    /**
     * Return all receipt for specific vehicle
     * @param string $numberPlate
     * @return ReceiptEntity[]
     */
    public function findAllReceiptByVehicleNumberPlate(string $numberPlate): array;

    public function findOpenReceiptByParkingIdAndVehicleNumberPlate(string $parkingId, string $numberPlate): ReceiptEntity;
}