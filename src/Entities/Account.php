<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use Illuminate\Support\Collection;

class Account extends BaseEntity
{
    protected string $endpoint = '/accounts';

    public function index(): Collection
    {
        return $this->baseIndex();
    }
}
