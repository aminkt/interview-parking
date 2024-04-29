<?php

namespace Temperworks\Codechallenge\Domain\Entity;

use Temperworks\Codechallenge\Domain\Exception\NoParkingSpotLeftException;
use Temperworks\Codechallenge\Domain\Exception\ValidationException;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class FloorEntity implements IEntity
{
    private float $currentCapacity;

    /**
     * @param string|null $id
     * @param int $capacity
     * @param EVehicleType[] $limitedVehicleTypes
     */
    public function __construct(
        private ?string $id,
        private int $capacity,
        private array $limitedVehicleTypes = []
    )
    {
        $this->currentCapacity = $this->capacity;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getCurrentCapacity(): float
    {
        return $this->currentCapacity;
    }

    public function getTotalCapacity()
    {
        return $this->capacity;
    }

    public function parkVehicle(EVehicleType $vehicleType)
    {
        if (in_array($vehicleType, $this->limitedVehicleTypes)) {
            throw new NoParkingSpotLeftException("{$vehicleType->name} is not allowed to park in the floor {$this->getId()}");
        }

        if ($this->getCurrentCapacity() < $vehicleType->requiredSpace()) {
            throw new NoParkingSpotLeftException("Sorry, no spaces left");
        }

        $this->currentCapacity -= $vehicleType->requiredSpace();
    }

    public function takeOutVehicle(EVehicleType $vehicleType)
    {
        if ($this->getCurrentCapacity() + $vehicleType->requiredSpace() > $this->getTotalCapacity()) {
            throw new ValidationException("Invalid operation. can not take out car because remaining space will not mach.");
        }

        $this->currentCapacity += $vehicleType->requiredSpace();
    }
}