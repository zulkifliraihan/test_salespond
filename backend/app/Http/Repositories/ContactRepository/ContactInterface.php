<?php

namespace App\Http\Repositories\ContactRepository;

use Illuminate\Contracts\Database\Query\Builder;

interface ContactInterface
{
    public function query(): Builder;

    public function create(array $data): array;

    public function update(int $id, array $data): array;

    public function delete(int $id): array;
}