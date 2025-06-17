<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallLog\CreateCallLogRequest;
use App\Http\Services\CallLogService;
use Illuminate\Http\Request;

class CallLogController extends Controller
{
    private $callLogService;

    /**
     * CallLogController constructor.
     *
     * @param $callLogService
     */
    public function __construct(CallLogService $callLogService)
    {
        $this->callLogService = $callLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $service = $this->callLogService->index($request->all());

            if ($service['status']) {
                return $this->success($service['response'], $service['data']);
            } else {
                if ($service['response'] == 'server') {
                    return $this->errorServer($service['errors']);
                } elseif ($service['response'] == 'not-found') {
                    return $this->errorNotFound($service['errors']);
                } else {
                    return $this->errorValidator($service['errors']);
                }
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $service = $this->callLogService->show($id);

            if ($service['status']) {
                return $this->success($service['response'], $service['data']);
            } else {
                if ($service['response'] == 'server') {
                    return $this->errorServer($service['errors']);
                } elseif ($service['response'] == 'not-found') {
                    return $this->errorNotFound($service['errors']);
                } else {
                    return $this->errorValidator($service['errors']);
                }
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCallLogRequest $request) 
    {
        try {
            $service = $this->callLogService->create($request->all());

            if ($service['status']) {
                return $this->success($service['response'], $service['data']);
            } else {
                if ($service['response'] == 'server') {
                    return $this->errorServer($service['errors']);
                } elseif ($service['response'] == 'not-found') {
                    return $this->errorNotFound($service['errors']);
                } else {
                    return $this->errorValidator($service['errors']);
                }
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }
}
