<?php

namespace Temperworks\Codechallenge\App\Query;


abstract class AQueryHandler
{
    abstract public function execute(?AQuery $query = null);
}