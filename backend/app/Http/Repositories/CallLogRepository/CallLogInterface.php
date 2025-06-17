<?php

namespace App\Http\Repositories\CallLogRepository;

use App\Models\CallLog;
use Illuminate\Contracts\Database\Query\Builder;

interface CallLogInterface
{
    public function query(): Builder;

    public function create(array $data): CallLog;
}