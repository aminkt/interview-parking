<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Command\TakeOutVehicleFromFloor;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommand;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommandHandler;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromFloor\TakeOutVehicleFromFloorCommand;
use Temperworks\Codechallenge\App\Command\TakeOutVehicleFromFloor\TakeOutVehicleFromFloorCommandHandler;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ParkingInMemoryRepository;

class TakeOutVehicleFromFloorCommandHandlerTest extends TestCase
{
    private TakeOutVehicleFromFloorCommandHandler $cmdHandler;
    private ParkingInMemoryRepository $parkingRepository;

    public function setUp(): void
    {
        // In Memory repositories are good candidates to be used as mock repo. no need to mock them because they are isolated in the test environment.
        $this->parkingRepository = new ParkingInMemoryRepository();
        $this->cmdHandler = new TakeOutVehicleFromFloorCommandHandler(parkingRepository: $this->parkingRepository);
    }

    /**
     * Test function naming follow [test_unitOfWork_stateUnderTest_expectedBehavior] pattern.
     */
    function test_AddVehicleToParking_WhenParkingHasSpace_ThenReserveTheParkingSpot()
    {
        $this->parkingRepository->save(new ParkingEntity("123", [
            new FloorEntity("123-0", 60),
            new FloorEntity("123-1", 50, [EVehicleType::Van]),
            new FloorEntity("123-2", 70, [EVehicleType::Van])
        ]));

        $command = TakeOutVehicleFromFloorCommand::fromArray([
            'parkingId' => '123',
            'vehicleType' => EVehicleType::Van
        ]);

        $this->cmdHandler->execute($command);

        /** @var ParkingEntity $parkingEntity */
        $parkingEntity = $this->parkingRepository->getById("123");
        $this->assertEquals($parkingEntity->getTotalCapacity(), $parkingEntity->getRemainingCapacity());
    }
}