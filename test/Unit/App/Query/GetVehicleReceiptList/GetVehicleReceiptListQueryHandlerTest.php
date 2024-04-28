<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Query\GetVehicleReceiptList;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Query\GetVehicleReceiptList\GetVehicleReceiptListQuery;
use Temperworks\Codechallenge\App\Query\GetVehicleReceiptList\GetVehicleReceiptListQueryHandler;
use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ReceiptInMemoryRepository;

class GetVehicleReceiptListQueryHandlerTest extends TestCase
{
    private GetVehicleReceiptListQueryHandler $queryHandler;
    private IReceiptRepository $receiptRepository;

    public function setUp(): void
    {
        $this->receiptRepository = new ReceiptInMemoryRepository();
        $this->queryHandler = new GetVehicleReceiptListQueryHandler($this->receiptRepository);
    }

    function test_GetVehicleReceipts_WhenNumberPlateProvided_ThenReturnListOfReceipts()
    {
        $this->receiptRepository->saveAll(...[
            new ReceiptEntity(null, "23r4t", EVehicleType::Motorcycle, "123", 2, new \DateTimeImmutable("-765 minutes"), new \DateTimeImmutable("-700 minutes")),
            new ReceiptEntity(null, "23r4t", EVehicleType::Motorcycle, "678", 0, new \DateTimeImmutable("-432 minutes"), new \DateTimeImmutable("-395 minutes")),
            new ReceiptEntity(null, "23r4t", EVehicleType::Motorcycle, "111", 4, new \DateTimeImmutable("-6543 minutes"), new \DateTimeImmutable("-6492 minutes")),
            new ReceiptEntity(null, "23r4t", EVehicleType::Motorcycle, "123", 2, new \DateTimeImmutable("-1023 minutes"), new \DateTimeImmutable("-993 minutes")),
            new ReceiptEntity(null, "23r4t", EVehicleType::Motorcycle, "111", 1, new \DateTimeImmutable("-20 minutes"), null),
        ]);

        $items = $this->queryHandler->execute(new GetVehicleReceiptListQuery("23r4t"));

        $this->assertEquals("Yes", $items[4]['isInPark']);
        $this->assertEquals("No", $items[0]['isInPark']);
        $this->assertEquals("20 minutes", $items[4]['parkTime']);
    }
}