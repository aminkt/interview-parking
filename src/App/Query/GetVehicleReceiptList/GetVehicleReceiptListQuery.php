<?php

namespace Temperworks\Codechallenge\App\Query\GetVehicleReceiptList;

use Temperworks\Codechallenge\App\Query\AQuery;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;

class GetVehicleReceiptListQuery extends AQuery
{
    public function __construct(public readonly string $vehicleNumberPlate)
    {
        if (empty($this->vehicleNumberPlate)) {
            throw new ValidationException(
                "VehicleNumberPlate can not be empty.",
                'vehicleNumberPlate',
                $this->vehicleNumberPlate
            );
        }
    }
}