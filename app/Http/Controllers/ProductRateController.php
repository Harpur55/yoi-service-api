<?php

namespace App\Http\Controllers;

use App\Repository\ProductRate\ProductRateRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;

class ProductRateController extends Controller
{
    protected $productRateRepository;

    public function __construct(ProductRateRepositoryInterface $productRateRepository)
    {
        $this->productRateRepository = $productRateRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $response = $this->productRateRepository->getAll(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }
}
