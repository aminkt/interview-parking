<?php

namespace Temperworks\Codechallenge\Test\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class ReceiptEntityTest extends TestCase
{
    function test_CalculateParkTime_WhenCarIsNotTakenOut_ThenParkTimeShouldBeCalculatedTillNow()
    {
        $entity = new ReceiptEntity("123", "3245", EVehicleType::Car, '12', 2, new \DateTimeImmutable("-22 minutes"), null);
        $time = $entity->calculateParkTime();
        $this->assertEquals(22, $time->i);
    }

    function test_CalculateParkTime_WhenCarIsTakenOut_ThenParkTimeShouldBeCalculatedTillTakeOutTime()
    {
        $entity = new ReceiptEntity("123", "3245", EVehicleType::Car, '12', 2, new \DateTimeImmutable("-22 minutes"), null);
        $entity->setTakeOutAt(new \DateTimeImmutable("-2 minutes"));
        $time = $entity->calculateParkTime();
        $this->assertEquals(20, $time->i);
    }
}