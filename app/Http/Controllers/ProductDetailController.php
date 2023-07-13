<?php

namespace App\Http\Controllers;

use App\Repository\ProductDetail\ProductDetailRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class ProductDetailController extends Controller
{
    protected $productDetailRepository;

    public function __construct(ProductDetailRepositoryInterface $productDetailRepository)
    {
        $this->productDetailRepository = $productDetailRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $response = $this->productDetailRepository->getAll(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
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
            $response = $this->productDetailRepository->getById($id);

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
