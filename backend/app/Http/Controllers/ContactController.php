<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Services\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private $contactService;

    /**
     * ContactController constructor.
     *
     * @param ContactService $contactService
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $service = $this->contactService->index($request->all());

            if ($service['status']) {
                return $this->success($service['response'], $service['data']);
            }
            else {
                if($service['response'] == 'server') {
                    return $this->errorServer($service['errors']);
                }
                else if($service['response'] == 'not-found') {
                    return $this->errorNotFound($service['errors']);
                }
                else {
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
            $service = $this->contactService->show($id);

            if ($service['status']) {
                return $this->success($service['response'], $service['data']);
            }
            else {
                if($service['response'] == 'server') {
                    return $this->errorServer($service['errors']);
                }
                else if($service['response'] == 'not-found') {
                    return $this->errorNotFound($service['errors']);
                }
                else {
                    return $this->errorValidator($service['errors']);
                }
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request, int $id)
    {
        try {
            $service = $this->contactService->update($request->all(), $id);

            if ($service['status']) {
                return $this->success($service['response'], $service['data']);
            }
            else {
                if($service['response'] == 'server') {
                    return $this->errorServer($service['errors']);
                }
                else if($service['response'] == 'not-found') {
                    return $this->errorNotFound($service['errors']);
                }
                else {
                    return $this->errorValidator($service['errors']);
                }
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }

    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function roles()
        {
        try {
            $service = $this->contactService->roles();

            if ($service['status']) {
                return $this->success($service['response'], $service['data']);
            }
            else {
                if($service['response'] == 'server') {
                    return $this->errorServer($service['errors']);
                }
                else if($service['response'] == 'not-found') {
                    return $this->errorNotFound($service['errors']);
                }
                else {
                    return $this->errorValidator($service['errors']);
                }
            }
        } catch (\Throwable $th) {
            return $this->errorServer($th->getMessage());
        }
    }
}
