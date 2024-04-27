<?php

namespace Temperworks\Codechallenge\Test\Unit\App\Command\AddVehicleToParking;

use PHPUnit\Framework\TestCase;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommand;
use Temperworks\Codechallenge\App\Command\AddVehicleToParking\AddVehicleToParkingCommandHandler;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\Entity\ReceiptEntity;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ParkingInMemoryRepository;
use Temperworks\Codechallenge\Infra\Repository\InMemory\ReceiptInMemoryRepository;

class AddVehicleToParkingCommandHandlerTest extends TestCase
{
    private AddVehicleToParkingCommandHandler $cmdHandler;
    private ParkingInMemoryRepository $parkingRepository;
    private ReceiptInMemoryRepository $receiptRepository;

    public function setUp(): void
    {
        // In Memory repositories are good candidates to be used as mock repo. no need to mock them because they are isolated in the test environment.
        $this->parkingRepository = new ParkingInMemoryRepository();
        $this->receiptRepository = new ReceiptInMemoryRepository();
        $this->cmdHandler = new AddVehicleToParkingCommandHandler(
            parkingRepository: $this->parkingRepository,
            receiptRepository: $this->receiptRepository
        );
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
            'vehicleNumberPlate' => '12rt56',
            'vehicleType' => EVehicleType::Van
        ]);

        $this->cmdHandler->execute($command);

        /** @var ParkingEntity $parkingEntity */
        $parkingEntity = $this->parkingRepository->getById("123");
        $this->assertEquals($parkingEntity->getTotalCapacity() - EVehicleType::Van->requiredSpace(), $parkingEntity->getRemainingCapacity());

        $receiptEntity = $this->receiptRepository->findOpenReceiptByParkingIdAndVehicleNumberPlate($command->parkingId, $command->vehicleNumberPlate);
        $this->assertEquals($receiptEntity->getParkingId(), $parkingEntity->getId());
        $this->assertEquals($receiptEntity->getVehicleNumberPlate(), $command->vehicleNumberPlate);
        $this->assertEquals($receiptEntity->getVehicleType(), $command->vehicleType);
        $this->assertNull($receiptEntity->getTakeOutAt());
    }

    function test_AddVehicleToParking_WhenItIsParkedBefore_ThenThrowValidateException()
    {
        $this->expectException(ValidationException::class);

        $this->parkingRepository->save(new ParkingEntity("123", [
            new FloorEntity("123-0", 60),
        ]));
        $this->receiptRepository->save(new ReceiptEntity(
            null,
            '12rt56',
            EVehicleType::Van,
            '123',
            0,
            new \DateTimeImmutable("now")
        ));

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '12rt56',
            'vehicleType' => EVehicleType::Van
        ]);

        $this->cmdHandler->execute($command);
    }

    function test_AddVehicleToParking_WhenParkingHasNoSpace_ThenThrowNoParkingLeftException()
    {
        $this->expectException(NoParkingSpotLeftException::class);

        $this->parkingRepository->save(new ParkingEntity("123", [
            new FloorEntity("123-0", 1),
            new FloorEntity("123-1", 1, [EVehicleType::Van]),
            new FloorEntity("123-2", 1, [EVehicleType::Van])
        ]));

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleNumberPlate' => '12rt56',
            'vehicleType' => EVehicleType::Van
        ]);

        $this->cmdHandler->execute($command);
    }

    function test_AddVehicleToParking_WhenParkingIdIsEmpty_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $this->parkingRepository->save(new ParkingEntity("123", [
            new FloorEntity("123-0", 1),
            new FloorEntity("123-1", 1, [EVehicleType::Van]),
            new FloorEntity("123-2", 1, [EVehicleType::Van])
        ]));

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '',
            'vehicleNumberPlate' => '12rt56',
            'vehicleType' => EVehicleType::Van
        ]);

        $this->cmdHandler->execute($command);
    }

    function test_AddVehicleToParking_WhenFloorNumberIsMoreThan2_ThenThrowValidationException()
    {
        $this->expectException(ValidationException::class);

        $this->parkingRepository->save(new ParkingEntity("123", [
            new FloorEntity("123-0", 1),
            new FloorEntity("123-1", 1, [EVehicleType::Van]),
            new FloorEntity("123-2", 1, [EVehicleType::Van]),
            new FloorEntity("123-3", 3, [EVehicleType::Van]),
        ]));

        $command = AddVehicleToParkingCommand::fromArray([
            'parkingId' => '123',
            'vehicleType' => EVehicleType::Van,
            'vehicleNumberPlate' => '12rt56',
            'floorNumber' => 4
        ]);

        $this->cmdHandler->execute($command);
    }
}