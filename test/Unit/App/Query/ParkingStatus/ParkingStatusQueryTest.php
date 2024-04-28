<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Query\ParkingStatus;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Query\ParkingStatus\ParkingStatusQuery;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;

class ParkingStatusQueryTest extends TestCase
{
    function test_ParkingStatusQuery_WhenInputIsCorrect_ThenNoErrorExpected()
    {
        $command = new ParkingStatusQuery("123");

        $this->assertEquals($command->parkingId, "123");
    }

    function test_ParkingStatusQuery_WhenVehicleNumberPlateIsWrong_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = new ParkingStatusQuery("");
    }
}