<?php

namespace Temperworks\Codechallenge\Domain\Entity;

interface IEntity
{
    public function getId(): ?string;

    public function setId(string $id): static;
}