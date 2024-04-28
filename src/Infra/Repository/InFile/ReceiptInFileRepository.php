<?php

namespace Temperworks\Codechallenge\Infra\Repository\InFile;

use Temperworks\Codechallenge\Domain\Repository\IReceiptRepository;
use Temperworks\Codechallenge\Infra\Repository\InMemory\TReceiptRepository;

class ReceiptInFileRepository extends AInFileRepository implements IReceiptRepository
{
    use TReceiptRepository;

    public static function getFilePath(): string
    {
        return '/app/runtime/repository/receipt.json';
    }
}