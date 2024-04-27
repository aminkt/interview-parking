<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Command\TakeOutVehicleFromFloor;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromFloor\TakeOutVehicleFromFloorCommand;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;

class TakeOutVehicleFromFloorCommandTest extends TestCase
{
    function test_CreateTakeOutVehicleFromFloorCommand_WhenInputIsCorrect_ThenNoErrorExpected()
    {
        $command = TakeOutVehicleFromFloorCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '123we43',
        ]);

        $this->assertEquals($command->parkingId, '123');
        $this->assertEquals($command->vehicleNumberPlate, '123we43');
    }

    function test_CreateTakeOutVehicleFromFloorCommand_WhenParkingIdIsEmpty_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = TakeOutVehicleFromFloorCommand::fromArray([
            'parkingId' => '',
            'vehicleNumberPlate' => '123we43',
        ]);
    }

    function test_CreateTakeOutVehicleFromFloorCommand_WhenVehicleNumberPlateIsWrong_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = TakeOutVehicleFromFloorCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '',
        ]);
    }
}