<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Command\TakeOutVehicleFromParking;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommand;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommandHandler;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking\TakeOutVehicleFromParkingCommand;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromParking\TakeOutVehicleFromParkingCommandHandler;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ParkingInMemoryRepository;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ReceiptInMemoryRepository;

class TakeOutVehicleFromParkingCommandHandlerTest extends TestCase
{
    private TakeOutVehicleFromParkingCommandHandler $cmdHandler;
    private AddVehicleToParkingCommandHandler $addVehicleCmdHandler;
    private IParkingRepository $parkingRepository;
    private IReceiptRepository $receiptRepository;

    public function setUp(): void
    {
        // In Memory repositories are good candidates to be used as mock repo. no need to mock them because they are isolated in the test environment.
        $this->parkingRepository = new ParkingInMemoryRepository();
        $this->receiptRepository = new ReceiptInMemoryRepository();
        $this->cmdHandler = new TakeOutVehicleFromParkingCommandHandler(
            parkingRepository: $this->parkingRepository,
            receiptRepository: $this->receiptRepository
        );
        $this->addVehicleCmdHandler = new AddVehicleToParkingCommandHandler(
            parkingRepository: $this->parkingRepository,
            receiptRepository: $this->receiptRepository
        );
    }

    /**
     * Test function naming follow [test_unitOfWork_stateUnderTest_expectedBehavior] pattern.
     */
    function test_TakeOutVehicleFromParking_WhenParkingHasSpace_ThenReserveTheParkingSpot()
    {
        $parkingEntity = new ParkingEntity("123", [
            new FloorEntity("123-0", 60),
            new FloorEntity("123-1", 50, [EVehicleType::Van]),
            new FloorEntity("123-2", 70, [EVehicleType::Van])
        ]);
        $this->parkingRepository->save($parkingEntity);
        $this->addVehicleCmdHandler->execute(new AddVehicleToParkingCommand(
            parkingId: "123",
            vehicleNumberPlate: "324rt5",
            vehicleType: EVehicleType::Motorcycle
        ));

        $parkingEntity = $this->parkingRepository->getById("123");
        $this->assertEquals(
            expected: $parkingEntity->getTotalCapacity() - EVehicleType::Motorcycle->requiredSpace(),
            actual: $parkingEntity->getRemainingCapacity()
        );


        $command = new TakeOutVehicleFromParkingCommand(parkingId: "123", vehicleNumberPlate: "324rt5");
        $this->cmdHandler->execute($command);

        $parkingEntity = $this->parkingRepository->getById("123");
        $this->assertEquals($parkingEntity->getTotalCapacity(), $parkingEntity->getRemainingCapacity());

        $receiptEntity = $this->receiptRepository->findAllReceiptByVehicleNumberPlate("324rt5")[0];
        $this->assertNotNull($receiptEntity->getTakeOutAt());
    }
}