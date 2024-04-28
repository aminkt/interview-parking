<?php

namespace Temperworks\Codechallenge\Cli\Command;

use Temperworks\Codechallenge\Cli\DC;
use Temperworks\Codechallenge\Domain\Entity\FloorEntity;
use Temperworks\Codechallenge\Domain\Entity\ParkingEntity;
use Temperworks\Codechallenge\Domain\ValueObject\EVehicleType;

class InstallCliCommand extends ACliCommand
{
    public static function name(): string
    {
        return "install";
    }

    public static function description(): string
    {
        return "Install application.";
    }

    public function execute(array $parameters)
    {
        if (DC::AppConfig()->parkingId) {
            $this->ptintNewLine("Application installed successfully. No action required.");
            return;
        }

        $this->ptintNewLine("Parking id is not defined. creating a sample one!");
        $entity = DC::parkingRepository()->save(new ParkingEntity("123", [
            new FloorEntity("123-0", 5),
            new FloorEntity("123-1", 10, [EVehicleType::Van]),
            new FloorEntity("123-2", 7, [EVehicleType::Van])
        ]));
        $this->ptintNewLine("Parking is created. creating .env file ...");
        file_put_contents("/app/.env", "PARKING_ID={$entity->getId()}");
        putenv("PARKING_ID={$entity->getId()}");
        $this->ptintNewLine("Application installed successfully.");
    }
}