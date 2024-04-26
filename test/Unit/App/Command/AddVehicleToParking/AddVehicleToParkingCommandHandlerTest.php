<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Command\AddVehicleToParking;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommand;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommandHandler;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ParkingInMemoryRepository;

class AddVehicleToParkingCommandHandlerTest extends TestCase
{
    private AddVehicleToParkingCommandHandler $cmdHandler;
    private ParkingInMemoryRepository $parkingRepository;

    public function setUp(): void
    {
        $this->parkingRepository = new ParkingInMemoryRepository();
        $this->cmdHandler = new AddVehicleToParkingCommandHandler(parkingRepository: $this->parkingRepository);
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

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleType' => EVehicleType::Van
        ]);

        $this->cmdHandler->execute($command);

        /** @var ParkingEntity $parkingEntity */
        $parkingEntity = $this->parkingRepository->getById("123");
        $this->assertEquals($parkingEntity->getTotalCapacity() - EVehicleType::Van->requiredSpace(), $parkingEntity->getRemainingCapacity());
    }

    function test_AddVehicleToParking_WhenParkingHasNoSpace_ThenThrowNoParkingLeftException()
    {
        $this->expectException(NoParkingSpotLeftException::class);

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleType' => EVehicleType::Car
        ]);

        $this->cmdHandler->execute($command);
    }
}