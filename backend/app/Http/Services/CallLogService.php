<?php

namespace App\Http\Services;

use App\Http\Repositories\CallLogRepository\CallLogInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CallLogService
{
    private $callLogRepository;

    /**
     * CallLogService constructor.
     *
     * @param CallLogInterface $callLogRepository
     */
    public function __construct(CallLogInterface $callLogRepository)
    {
        $this->callLogRepository = $callLogRepository;
    }

    /**
     * Get all call logs.
     *
     * @return mixed
     */
    public function index(array $request)
    {
        if (isset($request['filter'])) {
            $validator = Validator::make($request['filter'], [
                'start_date' => 'required_with:end_date|date',
                'end_date' => 'required_with:start_date|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                return [
                    'status' => false,
                    'response' => 'validation',
                    'errors' => [
                        'data' => $validator->errors()->toArray(),
                    ],
                ];
            }
        }

        $keyData = [
            'filters' => $request['filter'] ?? [],
            'limit' => $request['limit'] ?? 'all',
            'pagination' => $request['pagination'] ?? false,
            'page' => isset($request['page']) ? (int) $request['page'] : 1,
        ];

        $cacheKey = 'call_logs' . ':' . md5(serialize($keyData));
        
        // Try to get data from cache first (Cache-Aside Pattern)
        $cachedData = Cache::get($cacheKey);
        
        if ($cachedData !== null) {
            return [
                'status' => true,
                'response' => 'get',
                'data' => $cachedData,
            ];
        }

        $data = $this->callLogRepository->query()->with('contact');

        if (isset($request['filter'])) {
            $data->where(function ($query) use ($request) {
                if (isset($request['filter']['start_date']) && isset($request['filter']['end_date'])) {
                    $start = Carbon::parse($request['filter']['start_date'])->startOfDay();
                    $end = Carbon::parse($request['filter']['end_date'])->endOfDay();

                    $query->whereBetween('created_at', [$start, $end]);
                }
            });
        }

        $pagination = false;
        $limit = isset($request['limit']) ? $request['limit'] : 'all';

        if ($limit == 'all') {
            if (isset($request['pagination']) && $request['pagination'] == true) {
                $pagination = true;
                $clonedData = clone $data;
                $totalData = $clonedData->count();
                $data = $data->paginate($totalData);
            }
            else {
                $data = $data->get();
            }
        }
        else {
            if (isset($request['pagination']) && $request['pagination'] == true) {
                $pagination = true;
                $data = $data->paginate($limit);
            }
            else {
                $data = $data->limit($limit)->get();
            }
        }

        $responseData = [
            'paginate' => $pagination,
            'call_logs' => $data,
        ];

        // Store data in cache (Cache-Aside Pattern)
        Cache::tags(['call_logs'])->put($cacheKey, $responseData, 600);

        return [
            'status' => true,
            'response' => 'get',
            'data' => $responseData,
        ];
    }

    /**
     * Get a specific call log by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $data = $this->callLogRepository->query()->find($id);

        if (!$data) {
            return [
                'status' => false,
                'response' => 'not-found',
                'errors' => [
                    'data' => [
                        'Call log does not exist.',
                    ],
                ],
            ];
        }

        return [
            'status' => true,
            'response' => 'get',
            'data' => $data,
        ];
    }

    /**
     * Create a new call log.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $data = $this->callLogRepository->create($data);

        Cache::tags(['call_logs'])->flush();

        return [
            'status' => true,
            'response' => 'created',
            'data' => $data,
        ];
    }
}   