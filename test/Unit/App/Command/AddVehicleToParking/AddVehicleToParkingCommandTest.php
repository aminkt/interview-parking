<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Command\AddVehicleToParking;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommand;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommandHandler;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ParkingInMemoryRepository;

class AddVehicleToParkingCommandTest extends TestCase
{
    function test_CreateAddVehicleToParkingCommand_WhenParkingIdAndVehicleTypeIsProvidedCorrectly_ThenNoErrorExpected()
    {
        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '32435',
            'vehicleType' => EVehicleType::Van
        ]);

        $this->assertEquals($command->parkingId, '123');
        $this->assertEquals($command->vehicleType, EVehicleType::Van);
    }

    function test_CreateAddVehicleToParkingCommand_WhenParkingIdIsEmpty_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '',
            'vehicleNumberPlate' => '32435',
            'vehicleType' => EVehicleType::Van
        ]);
    }

    function test_CreateAddVehicleToParkingCommand_WhenVehicleNumberPlateIsEmpty_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '124',
            'vehicleNumberPlate' => '',
            'vehicleType' => EVehicleType::Van
        ]);
    }

    function test_CreateAddVehicleToParkingCommand_WhenVehicleTypeIsWrong_ThenThrowValidationException()
    {
        $this->expectException(\TypeError::class);

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '',
            'vehicleNumberPlate' => '32435',
            'vehicleType' => 'van'
        ]);
    }

    function test_CreateAddVehicleToParkingCommand_WhenFloorNumberIsMoreThan2_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '32435',
            'vehicleType' => EVehicleType::Van,
            'floorNumber' => 4
        ]);
    }
}