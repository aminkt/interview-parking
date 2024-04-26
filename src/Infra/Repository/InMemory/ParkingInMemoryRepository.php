<?php

namespace Temperworks\Codechallenge\Infra\Repository\InMemory;

use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;

class ParkingInMemoryRepository extends AInMemoryRepo implements IParkingRepository
{
    public static function getAggregateEntityName(): string
    {
        return ParkingEntity::class;
    }
}