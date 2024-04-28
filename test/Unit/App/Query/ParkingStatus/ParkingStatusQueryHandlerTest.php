<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Query\ParkingStatus;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommand;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommandHandler;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking\TakeOutVehicleFromParkingCommand;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking\TakeOutVehicleFromParkingCommandHandler;
use Temperworks\Codechallenge\App\Query\ParkingStatus\ParkingStatusQuery;
use Temperworks\Codechallenge\App\Query\ParkingStatus\ParkingStatusQueryHandler;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ParkingInMemoryRepository;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ReceiptInMemoryRepository;

class ParkingStatusQueryHandlerTest extends TestCase
{
    private IParkingRepository $parkingRepository;
    private IReceiptRepository $receiptRepository;
    private ParkingStatusQueryHandler $handler;
    private AddVehicleToParkingCommandHandler $addVehicleToParkingCommandHandler;
    private TakeOutVehicleFromParkingCommandHandler $takeOutVehicleFromParkingCommandHandler;

    public function setUp(): void
    {
        $this->parkingRepository = new ParkingInMemoryRepository();
        $this->receiptRepository = new ReceiptInMemoryRepository();
        $this->handler = new ParkingStatusQueryHandler($this->parkingRepository, $this->receiptRepository);
        $this->addVehicleToParkingCommandHandler = new AddVehicleToParkingCommandHandler($this->parkingRepository, $this->receiptRepository);
        $this->takeOutVehicleFromParkingCommandHandler = new TakeOutVehicleFromParkingCommandHandler($this->parkingRepository, $this->receiptRepository);
    }

    function test_ParkingStatusQueryHandler_InHappyScenario_ThenNoErrorExpected()
    {
        // Prepare state
        $this->parkingRepository->save(new ParkingEntity("123", [
            new FloorEntity("123-0", 60),
            new FloorEntity("123-1", 50, [EVehicleType::Van]),
            new FloorEntity("123-2", 70, [EVehicleType::Van])
        ]));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "566tt66", EVehicleType::Motorcycle, 2));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "56765t6", EVehicleType::Van, 0));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "jhs7828", EVehicleType::Car, 1));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "90nijkm", EVehicleType::Car, 0));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "jkjns88", EVehicleType::Van, 0));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "89uj888", EVehicleType::Motorcycle, 1));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "00sjk88", EVehicleType::Van, 0));
        $this->addVehicleToParkingCommandHandler->execute(new AddVehicleToParkingCommand("123", "6yhu774", EVehicleType::Car, 2));

        $this->takeOutVehicleFromParkingCommandHandler->execute(new TakeOutVehicleFromParkingCommand("123", "56765t6"));
        $this->takeOutVehicleFromParkingCommandHandler->execute(new TakeOutVehicleFromParkingCommand("123", "90nijkm"));
        $this->takeOutVehicleFromParkingCommandHandler->execute(new TakeOutVehicleFromParkingCommand("123", "00sjk88"));

        $response = $this->handler->execute(new ParkingStatusQuery("123"));

        $this->assertNotEmpty($response);
        $this->assertEquals(180, $response['totalCapacity']);
        $this->assertEquals(175.5, $response['remainingCapacity']);
        $this->assertEquals(3, $response['floorCount']);
        $this->assertEquals(5, $response['vehiclesInParkCount']);
        $this->assertNotEmpty($response['floors']);
    }
}