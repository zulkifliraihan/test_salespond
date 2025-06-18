<?php

namespace App\Http\Services;

use App\Http\Repositories\ContactRepository\ContactInterface;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class ContactService
{
    private $contactRepository;

    /**
     * ContactService constructor.
     *
     * @param ContactInterface $contactRepository
     */
    public function __construct(ContactInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Get all contacts.
     *
     * @return mixed
     */
    public function index(array $request)
    {
        if (isset($request['filter'])) {
            if (isset($request['filter']['is_favorite'])) {
                if (
                    !in_array($request['filter']['is_favorite'], ['true', 'false', '1', '0', 1, 0], true)
                ) {
                    return [
                        'status' => false,
                        'response' => 'validator',
                        'errors' => [
                            'data' => ['is_favorite must be a boolean value.'],
                        ],
                    ];
                }

                $request['filter']['is_favorite'] = filter_var($request['filter']['is_favorite'], FILTER_VALIDATE_BOOLEAN);
            }
        }

         $keyData = [
            'filters' => $request['filter'] ?? [],
            'limit' => $request['limit'] ?? 'all',
            'pagination' => $request['pagination'] ?? false,
            'page' => isset($request['page']) ? (int) $request['page'] : 1,
        ];
        
        $cacheKey = 'contacts' . ':' . md5(serialize($keyData));

        // Try to get data from cache first (Cache-Aside Pattern)
        $cachedData = Cache::get($cacheKey);
        
        if ($cachedData !== null) {
            return [
                'status' => true,
                'response' => 'get',
                'data' => $cachedData,
            ];
        }

        $data = $this->contactRepository->query();
        
        if (isset($request['filter'])) {
            $data->where(function ($query) use ($request) {
                if (isset($request['filter']['company'])) {
                    $query->where('company', 'like', '%' . $request['filter']['company'] . '%');
                }
                if (isset($request['filter']['role_id'])) {
                    $query->where('role_id', $request['filter']['role_id']);
                }

                if (isset($request['filter']['is_favorite'])) {
                    $query->where('is_favorite', $request['filter']['is_favorite']);
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
            } else {
                $data = $data->get();
            }
        } else {
            if (isset($request['pagination']) && $request['pagination'] == true) {
                $pagination = true;
                $data = $data->paginate($limit);
            } else {
                $data = $data->limit($limit)->get();
            }
        }

        $responseData = [
            'paginate' => $pagination,
            'contacts' => $data,
        ];

        // Store data in cache (Cache-Aside Pattern)
        Cache::tags(['contacts'])->put($cacheKey, $responseData, 600);

        return [
            'status' => true,
            'response' => 'get',
            'data' => $responseData,
        ];
    }

    // public function index(array $request)
    // {
    //     $data = $this->contactRepository->query();

    //     if (isset($request['filter'])) {
    //         $data->where(function ($query) use ($request) {
    //             if (isset($request['filter']['company'])) {
    //                 $query->where('company', 'like', '%' . $request['filter']['company'] . '%');
    //             }
    //             if (isset($request['filter']['role_id'])) {
    //                 $query->where('role_id', $request['filter']['role_id']);
    //             }
    //         });
    //     }

    //     $pagination = false;
    //     $limit = isset($request['limit']) ? $request['limit'] : 'all';

    //     if ($limit == 'all') {
    //         if (isset($request['pagination']) && $request['pagination'] == true) {
    //             $pagination = true;
    //             $clonedData = clone $data;
    //             $totalData = $clonedData->count();
    //             $data = $data->paginate($totalData);
    //         }
    //         else {
    //             $data = $data->get();
    //         }
    //     }
    //     else {
    //         if (isset($request['pagination']) && $request['pagination'] == true) {
    //             $pagination = true;
    //             $data = $data->paginate($limit);
    //         }
    //         else {
    //             $data = $data->limit($limit)->get();
    //         }
    //     }

    //     return [
    //         'status' => true,
    //         'response' => 'get',
    //         'data' => [
    //             'paginate' => $pagination,
    //             'contacts' => $data,
    //         ],
    //     ];
    // }

    /**
     * Get a specific contact by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        $data = $this->contactRepository->query()->find($id);

        if (!$data) {
            return [
                'status' => false,
                'response' => 'not-found',
                'errors' => [
                    'data' => [
                        'Contact does not exist.',
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
     * Update a specific contact by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function update(array $request, int $id)
    {
        $data = $this->contactRepository->query()->find($id);

        if (!$data) {
            return [
                'status' => false,
                'response' => 'not-found',
                'errors' => [
                    'data' => [
                        'Contact does not exist.',
                    ],
                ],
            ];
        }

        $data->update($request);

        Cache::tags(['contacts'])->flush();

        return [
            'status' => true,
            'response' => 'update',
            'data' => $data,
        ];
    }
    
    /**
     * Get all roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function roles()
    {
        $data = Role::all();
        return [
            'status' => true,
            'response' => 'get',
            'data' => $data,
        ];
    }
}