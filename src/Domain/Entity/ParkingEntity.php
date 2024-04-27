<?php

namespace Temperworks\Codechallenge\Domain\Entity;

use OutOfRangeException;
use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class ParkingEntity implements IEntity
{
    /**
     * @param string|null $id
     * @param FloorEntity[] $floorEntities
     */
    public function __construct(
        private ?string $id,
        private array $floorEntities = []
    )
    {}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Find proper place for the vehicle.
     * @param EVehicleType $vehicleType
     * @return int Floor number which car is going to park.
     * @throws NoParkingSpotLeftException
     */
    public function parkVehicle(EVehicleType $vehicleType): int
    {
        if (empty($this->floorEntities)) {
            throw new NoParkingSpotLeftException("Parking({$this->id}) has no floor!");
        }

        for ($i = array_key_last($this->floorEntities); $i >= 0; $i--) {
            try {
                $this->parkVehicleInFloor($vehicleType, $i);
                return $i;
            } catch (NoParkingSpotLeftException) {
                // Nothing to do. Exception is caught and continue to next floor.
            }
        }

        throw new NoParkingSpotLeftException("Sorry, no spaces left");
    }

    public function parkVehicleInFloor(EVehicleType $vehicleType, int $floorNumber): void
    {
        $floorEntity = $this->floorEntities[$floorNumber] ?? null;
        if ($floorEntity === null) {
            throw new OutOfRangeException("Floor number {$floorNumber} is not valid! valid floor number range is from 0 to " . array_key_last($this->floorEntities));
        }
        $floorEntity->parkVehicle($vehicleType);
    }

    public function takeOutVehicleFromFloor(EVehicleType $vehicleType, int $floorNumber): void
    {
        $this->floorEntities[$floorNumber]->takeOutVehicle($vehicleType);
    }

    public function getRemainingCapacity(): float
    {
        $result = 0;
        foreach ($this->floorEntities as $floorEntity) {
            $result += $floorEntity->getCurrentCapacity();
        }
        return $result;
    }

    public function getTotalCapacity(): float
    {
        $result = 0;
        foreach ($this->floorEntities as $floorEntity) {
            $result += $floorEntity->getTotalCapacity();
        }
        return $result;
    }
}