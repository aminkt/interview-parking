<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Query\GetVehicleReceiptList;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Query\GetVehicleReceiptList\GetVehicleReceiptListQuery;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;

class GetVehicleReceiptListQueryTest extends TestCase
{
    function test_GetVehicleReceiptListQuery_WhenInputIsCorrect_ThenNoErrorExpected()
    {
        $command = GetVehicleReceiptListQuery::fromArray([
            'vehicleNumberPlate' => '123we43',
        ]);

        $this->assertEquals($command->vehicleNumberPlate, '123we43');
    }

    function test_GetVehicleReceiptListQuery_WhenVehicleNumberPlateIsWrong_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $command = GetVehicleReceiptListQuery::fromArray([
            'vehicleNumberPlate' => '',
        ]);
    }
}