<?php

namespace Temperworks\Codechallenge\Domain\Exception;

class EntityNotFoundException extends \Exception
{
    public function __construct(string $entityClass, string ...$entityIds)
    {
        $ids = implode(',', $entityIds);
        parent::__construct("{$entityClass} with id {$ids} not found!");
    }
}