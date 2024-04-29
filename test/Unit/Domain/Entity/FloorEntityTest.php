<?php

namespace Temperworks\Codechallenge\Test\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class FloorEntityTest extends TestCase
{
    function test_ParkVehicle_WhenParkCarAndHaveSpace_ThenNoExceptionExpected()
    {
        $entity = new FloorEntity("123", 5, []);
        $entity->parkVehicle(EVehicleType::Car);
        $this->assertEquals(4, $entity->getCurrentCapacity());
    }

    function test_ParkVehicle_WhenParkVanAndHasNoSpace_ThenNoParkingSpotLeftException()
    {
        $this->expectException(NoParkingSpotLeftException::class);
        $entity = new FloorEntity("123", 1, []);
        $entity->parkVehicle(EVehicleType::Van);
    }

    function test_ParkVehicle_WhenParkVanAndHasSpaceButVanIsLimited_ThenNoParkingSpotLeftException()
    {
        $this->expectException(NoParkingSpotLeftException::class);
        $entity = new FloorEntity("123", 5, [EVehicleType::Van]);
        $entity->parkVehicle(EVehicleType::Van);
    }

    function test_TakeOutVehicle_WhenCarParked_ThenNoExceptionExpected()
    {
        $entity = new FloorEntity("123", 5, []);
        $entity->parkVehicle(EVehicleType::Car);

        $entity->takeOutVehicle(EVehicleType::Car);
        $this->assertEquals(5, $entity->getCurrentCapacity());
    }

    function test_TakeOutVehicle_WhenIsNotParked_ThenExpectedValidationException()
    {
        $this->expectException(ValidationException::class);
        $entity = new FloorEntity("123", 5, []);
        $entity->takeOutVehicle(EVehicleType::Car);
    }
}