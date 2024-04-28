<?php

namespace Temperworks\Codechallenge\Cli;

use Temperworks\Codechallenge\Domain\Repository\IParkingRepository;
use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;
use Temperworks\Codechallenge\Infra\Repository\InFile\ParkingInFileRepository;
use Temperworks\Codechallenge\Infra\Repository\InFile\ReceiptInFileRepository;

final class DC
{
    private static array $container = [];

    protected static function getOrCreateDependency(string $dependencyClass)
    {
        $dependency = static::$container[$dependencyClass] ?? null;
        if ($dependency == null) {
            $dependency = new $dependencyClass();
            static::$container[$dependencyClass] = $dependency;
        }
        return$dependency;
    }

    public static function AppConfig(): AppConfig
    {
        return self::getOrCreateDependency(AppConfig::class);
    }

    public static function parkingRepository(): IParkingRepository
    {
        return self::getOrCreateDependency(ParkingInFileRepository::class);
    }

    public static function receiptRepository(): IReceiptRepository
    {
        return self::getOrCreateDependency(ReceiptInFileRepository::class);
    }
}