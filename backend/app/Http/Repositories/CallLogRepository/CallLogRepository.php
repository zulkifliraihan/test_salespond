<?php

namespace App\Http\Repositories\CallLogRepository;

use App\Models\CallLog;
use Illuminate\Contracts\Database\Query\Builder;

class CallLogRepository implements CallLogInterface
{
    private $callLog;

    public function __construct(CallLog $callLog)
    {
        $this->callLog = $callLog;
    }

    public function query(): Builder
    {
        return $this->callLog->query();
    }

    public function create(array $data): CallLog
    {
        return $this->callLog->create($data);
    }
}