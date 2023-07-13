<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoreRequest;
use App\Repository\Store\StoreRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    protected $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $response = $this->storeRepository->getAll(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreStoreRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->storeRepository->create($request);

            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $response = $this->storeRepository->getById($id);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }
}
