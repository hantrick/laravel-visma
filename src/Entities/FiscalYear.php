<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;

class FiscalYear extends BaseEntity
{
    protected string $endpoint = '/fiscalyears';

    public function index(): Collection
    {
        return $this->baseIndex();
    }
}
