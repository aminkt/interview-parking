<?php

namespace Temperworks\Codechallenge\App\Query\ParkingStatus;

use Temperworks\Codechallenge\App\Query\AQuery;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;

class ParkingStatusQuery extends AQuery
{
    public function __construct(public readonly string $parkingId)
    {
        if (empty($this->parkingId)) {
            throw new ValidationException("Parking id is required", "parkingId", $this->parkingId);
        }
    }
}