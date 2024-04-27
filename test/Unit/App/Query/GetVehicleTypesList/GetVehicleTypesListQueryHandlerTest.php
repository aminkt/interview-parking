<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Query\GetVehicleTypesList;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Query\GetVehicleTypesList\GetVehicleTypesListQueryHandler;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class GetVehicleTypesListQueryHandlerTest extends TestCase
{
    private GetVehicleTypesListQueryHandler $queryHandler;

    public function setUp(): void
    {
        $this->queryHandler = new GetVehicleTypesListQueryHandler();
    }

    function test_GetVehicleTypes_WhenQueryIt_ThenReturnListOfVehicleTypes()
    {
        $items = $this->queryHandler->execute();
        $this->assertCount(3, $items);

        $this->assertContains([
            'name' => EVehicleType::Motorcycle->name,
            'requiredSpace' => 0.5
        ], $items);
        $this->assertContains([
            'name' => EVehicleType::Car->name,
            'requiredSpace' => 1.
        ], $items);
        $this->assertContains([
            'name' => EVehicleType::Van->name,
            'requiredSpace' => 1.5
        ], $items);
    }
}