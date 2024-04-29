<?php

namespace Temperworks\Codechallenge\Test\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class ParkingEntityTest extends TestCase
{
    function test_ParkVehicle_WhenParkCarAndHaveSpace_ThenNoExceptionExpected()
    {
        $entity = new ParkingEntity("123", [
            new FloorEntity('123-1', 5),
            new FloorEntity('123-1', 4, [EVehicleType::Van]),
            new FloorEntity('123-1', 2, [EVehicleType::Van, EVehicleType::Motorcycle]),
        ]);
        $entity->parkVehicle(EVehicleType::Car);
        $this->assertEquals(10, $entity->getRemainingCapacity());
    }

    function test_ForceParkVehicleInFloor_WhenParkCarAndHaveSpace_ThenNoExceptionExpected()
    {
        $entity = new ParkingEntity("123", [
            new FloorEntity('123-1', 5),
            new FloorEntity('123-1', 4, [EVehicleType::Van]),
            new FloorEntity('123-1', 2, [EVehicleType::Van, EVehicleType::Motorcycle]),
        ]);
        $entity->parkVehicleInFloor(EVehicleType::Car, 1);
        $this->assertEquals(10, $entity->getRemainingCapacity());
        $this->assertEquals(3, $entity->getFloorEntities()[1]->getCurrentCapacity());
    }

    function test_ParkVehicle_WhenParkVanAndHasNoSpace_ThenNoParkingSpotLeftException()
    {
        $this->expectException(NoParkingSpotLeftException::class);
        $entity = new ParkingEntity("123", [
            new FloorEntity('123-1', 1),
            new FloorEntity('123-1', 4, [EVehicleType::Van]),
            new FloorEntity('123-1', 2, [EVehicleType::Van, EVehicleType::Motorcycle]),
        ]);
        $entity->parkVehicle(EVehicleType::Van);
    }

    function test_ParkVehicle_WhenParkVanAndHasSpaceButVanIsLimited_ThenNoParkingSpotLeftException()
    {
        $this->expectException(NoParkingSpotLeftException::class);
        $entity = new ParkingEntity("123", [
            new FloorEntity('123-1', 1),
            new FloorEntity('123-1', 4, [EVehicleType::Van]),
            new FloorEntity('123-1', 2, [EVehicleType::Van, EVehicleType::Motorcycle]),
        ]);
        $entity->parkVehicleInFloor(EVehicleType::Van, 2);
    }

    function test_TakeOutVehicle_WhenCarParked_ThenNoExceptionExpected()
    {
        $entity = new ParkingEntity("123", [
            new FloorEntity('123-1', 1),
            new FloorEntity('123-1', 4, [EVehicleType::Van]),
            new FloorEntity('123-1', 2, [EVehicleType::Van, EVehicleType::Car]),
        ]);
        $floorNumber = $entity->parkVehicle(EVehicleType::Car);
        $this->assertNotEquals(2, $floorNumber);

        $entity->takeOutVehicleFromFloor(EVehicleType::Car, $floorNumber);
        $this->assertEquals(7, $entity->getRemainingCapacity());
    }

    function test_TakeOutVehicle_WhenIsNotParked_ThenExpectedValidationException()
    {
        $this->expectException(ValidationException::class);

        $entity = new ParkingEntity("123", [
            new FloorEntity('123-1', 1),
            new FloorEntity('123-1', 4, [EVehicleType::Van]),
            new FloorEntity('123-1', 2, [EVehicleType::Van, EVehicleType::Motorcycle]),
        ]);

        $entity->takeOutVehicleFromFloor(EVehicleType::Car, 1);
    }
}