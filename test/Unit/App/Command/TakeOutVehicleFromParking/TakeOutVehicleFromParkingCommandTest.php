<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Command\TakeOutVehicleFromParking;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking\TakeOutVehicleFromParkingCommand;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;

class TakeOutVehicleFromParkingCommandTest extends TestCase
{
    function test_TakeOutVehicleFromParkingCommand_WhenInputIsCorrect_ThenNoErrorExpected()
    {
        $command = TakeOutVehicleFromParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '123we43',
        ]);

        $this->assertEquals($command->parkingId, '123');
        $this->assertEquals($command->vehicleNumberPlate, '123we43');
    }

    function test_TakeOutVehicleFromParkingCommand_WhenParkingIdIsEmpty_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = TakeOutVehicleFromParkingCommand::fromArray([
            'parkingId' => '',
            'vehicleNumberPlate' => '123we43',
        ]);
    }

    function test_TakeOutVehicleFromParkingCommand_WhenVehicleNumberPlateIsWrong_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = TakeOutVehicleFromParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '',
        ]);
    }
}