<?php

namespace Temperworks\Codechallenge\App\Query\GetVehicleTypesList;


use Temperworks\Codechallenge\App\Query\AQuery;
use Temperworks\Codechallenge\App\Query\AQueryHandler;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class GetVehicleTypesListQueryHandler extends AQueryHandler
{

    // This is a good use case for queries which not support query parameters.
    public function execute(GetVehicleTypesListQuery|AQuery $query)
    {
        $types = [EVehicleType::Motorcycle, EVehicleType::Car, EVehicleType::Van];
        $response = [];
        foreach ($types as $type) {
            /** @var EVehicleType $type */
            $response[] = [
                'name' => $type->name,
                'requiredSpace' => $type->requiredSpace()
            ];
        }
        return $response;
    }
}