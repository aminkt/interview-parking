<?php

namespace Temperworks\Codechallenge\Domain\Repository;

use Temperworks\Codechallenge\Domain\Entity\IEntity;
use Temperworks\Codechallenge\Domain\Exception\EntityNotFoundException;

interface IRepository
{

    /**
     * Find and return Entity by ids.
     *
     * @param string[] $ids
     * @return IEntity[]
     */
    public function getByIds(string ...$ids): array;

    /**
     * Find and return Entity by id.
     *
     * @param string $id
     * @return IEntity
     */
    public function getById(string $id): IEntity;

    /**
     * Return all items
     * @return IEntity[]
     */
    public function getAll(): array;

    /**
     * @see self::save()
     * @param IEntity[]  $entities
     */
    public function saveAll(IEntity ...$entities): array;

    public function save(IEntity $entity): IEntity;

    /**
     * Remove entities from repository
     * @param string ...$ids
     * @throws EntityNotFoundException
     */
    public function delete(string ...$ids): void;
}