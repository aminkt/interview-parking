<?php

namespace Temperworks\Codechallenge\Domain\Repository;

use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;

/**
 * @method ParkingEntity[] getByIds(string ...$ids)
 * @method ParkingEntity getById(string $id)
 * @method ParkingEntity save(ParkingEntity $entities)
 * @method ParkingEntity[] saveAll(ParkingEntity ...$entities)
 */
interface IParkingRepository extends IRepository
{

}