<?php

namespace Temperworks\Codechallenge\Infra\Repository\InFile;

use Temperworks\Codechallenge\Domain\Entity\IEntity;
use Temperworks\Codechallenge\Infra\Repository\InMemory\AInMemoryRepo;

abstract class AInFileRepository extends AInMemoryRepo
{
    public function __construct()
    {
        $this->loadFromFile();
    }

    abstract public static function getFilePath(): string;

    public function loadFromFile()
    {
        if (is_file(static::getFilePath())) {
            $data = json_decode(file_get_contents(static::getFilePath()), true);
        } else {
            $data = [];
        }
        $entities = $data['entities'] ?? null;
        $this->entities = $entities ? unserialize($entities) : [];
        $this->lastIdGenerated = $data['lastIdGenerated'] ?? 0;
    }

    public function saveAll(IEntity ...$entity): array
    {
        $res = parent::saveAll(...$entity);
        $this->storeToFile();
        return $res;
    }

    public function storeToFile()
    {
        $directoryPath = dirname(static::getFilePath());
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }
        file_put_contents(static::getFilePath(), json_encode([
            'entities' => serialize($this->entities),
            'lastIdGenerated' => $this->lastIdGenerated
        ]));
    }
}