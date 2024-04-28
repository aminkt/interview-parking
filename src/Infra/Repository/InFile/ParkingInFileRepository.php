<?php

namespace Temperworks\Codechallenge\Infra\Repository\InFile;

use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;

class ParkingInFileRepository extends AInFileRepository implements IParkingRepository
{
    public static function getAggregateEntityName(): string
    {
        return ParkingEntity::class;
    }

    public static function getFilePath(): string
    {
        return '/app/runtime/repository/parking.json';
    }
}