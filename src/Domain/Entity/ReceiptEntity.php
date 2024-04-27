<?php

namespace Temperworks\Codechallenge\Domain\Entity;

use DateInterval;
use DateTimeImmutable;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class ReceiptEntity implements IEntity
{
    public function __construct(
        private ?string                    $id,
        private readonly string            $vehicleNumberPlate,
        private readonly EVehicleType      $vehicleType,
        private readonly string            $parkingId,
        private readonly int               $floorNumber,
        private readonly DateTimeImmutable $parkAt,
        private ?DateTimeImmutable         $takeOutAt = null
    )
    {
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

    public function getVehicleNumberPlate(): string
    {
        return $this->vehicleNumberPlate;
    }

    public function getVehicleType(): EVehicleType
    {
        return $this->vehicleType;
    }

    public function getParkingId(): string
    {
        return $this->parkingId;
    }

    public function getFloorNumber(): int
    {
        return $this->floorNumber;
    }

    public function getParkAt(): DateTimeImmutable
    {
        return $this->parkAt;
    }

    public function getTakeOutAt(): ?DateTimeImmutable
    {
        return $this->takeOutAt;
    }

    public function setTakeOutAt(DateTimeImmutable $takeOutAt): self
    {
        $this->takeOutAt = $takeOutAt;
        return $this;
    }

    public function calculateParkTime(): DateInterval
    {
        $from = $this->takeOutAt ?: new DateTimeImmutable("now");
        return $from->diff($this->parkAt);
    }
}