<?php

namespace Temperworks\Codechallenge\Infra\Repository\InMemory;

use Temperworks\Codechallenge\Domain\Entity\IEntity;
use Temperworks\Codechallenge\Domain\Exception\EntityNotFoundException;
use Temperworks\Codechallenge\Domain\Repository\IRepository;

abstract class AInMemoryRepo implements IRepository
{
    /** @var IEntity[] */
    protected array $entities = [];
    protected int $lastIdGenerated = 100;

    public abstract static function getAggregateEntityName(): string;

    public function getAll(): array
    {
        return $this->entities;
    }

    public function getByIds(...$ids): array
    {
        return array_filter($this->entities, function (IEntity $entity) use ($ids) {
            return in_array($entity->getId(), $ids);
        });
    }

    public function getById(string $id): IEntity
    {
        $entities = $this->getByIds($id);
        if ($entities) {
            return array_pop($entities);
        }
        throw new EntityNotFoundException(static::getAggregateEntityName(), $id);
    }

    private function getNextId(): string
    {
        return ++$this->lastIdGenerated;
    }

    public function saveAll(IEntity ...$entities): array
    {
        $changedIds = [];
        foreach ($entities as $entity) {
            if ($entity::class !== static::getAggregateEntityName()) {
                throw new \Exception(static::class . " Can not handle " . $entity::class . ". Valid entity is " . static::getAggregateEntityName());
            }
            if ($entity->getId() == null) {
                $id = $this->getNextId();
                $entity->setId($id);
            }
            $changedIds[] = $entity->getId();
            $this->entities[$entity->getId()] = $entity;
        }
        return $this->getByIds(...$changedIds);
    }

    public function save(IEntity $entity): IEntity
    {
        $res = $this->saveAll($entity);
        return array_pop($res);
    }

    public function delete(string ...$ids): void
    {
        foreach ($ids as $id) {
            $entity = $this->entities[$id] ?? null;
            if ($entity) {
                unset($this->entities[$id]);
            } else {
                throw new EntityNotFoundException(static::getAggregateEntityName(), $id);
            }
        }
    }
}