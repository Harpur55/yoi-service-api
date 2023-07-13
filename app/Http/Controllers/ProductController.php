<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Repository\Product\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $response = $this->productRepository->getAll(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->productRepository->create($request);

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
            $response = $this->productRepository->getById($id);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    /**
     * Display a listing of the most popular.
     *
     * @return JsonResponse
     */
    public function fetchPopular(): JsonResponse
    {
        try {
            $response = $this->productRepository->fetchPopular(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function fetchBySeller(): JsonResponse
    {
        try {
            $response = $this->productRepository->fetchBySeller();

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function like(): JsonResponse
    {
        try {
            $response = $this->productRepository->like(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function unlike(): JsonResponse
    {
        try {
            $response = $this->productRepository->unlike(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function fetchPopularForSeller(): JsonResponse
    {
        try {
            $response = $this->productRepository->fetchPopularForSeller(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->productRepository->update($request);

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

    public function delete($id)
    {
        try {
            $response = $this->productRepository->delete($id);

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }
}
