<?php

namespace Temperworks\Codechallenge\Infra\Repository\InMemory;

use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;

class ReceiptInMemoryRepository extends AInMemoryRepo implements IReceiptRepository
{
    use TReceiptRepository;
}