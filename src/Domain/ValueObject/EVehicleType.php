<?php

namespace Temperworks\Codechallenge\Domain\ValueObject;

enum EVehicleType
{
    case Van;
    case Car;
    case Motorcycle;

    private const VehicleRequiredSpace = [
        EVehicleType::Motorcycle->name => 0.5,
        EVehicleType::Car->name => 1,
        EVehicleType::Van->name => 1.5,
    ];

    public function requiredSpace(): float
    {
        return self::VehicleRequiredSpace[$this->name];
    }
}
