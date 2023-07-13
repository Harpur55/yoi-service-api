<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellerBankAccountRequest;
use App\Http\Requests\UpdateSellerBankAccountRequest;
use App\Models\SellerBankAccount;
use App\Repository\SellerBankAccount\SellerBankAccountRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SellerBankAccountController extends Controller
{
    protected $sellerBankAccountRepository;

    public function __construct(SellerBankAccountRepositoryInterface $sellerBankAccountRepository)
    {
        $this->sellerBankAccountRepository = $sellerBankAccountRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $response = $this->sellerBankAccountRepository->getAll(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSellerBankAccountRequest $request
     * @return JsonResponse
     */
    public function store(StoreSellerBankAccountRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->sellerBankAccountRepository->create($request);

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
            $response = $this->sellerBankAccountRepository->getById($id);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function changeStatus($id)
    {
        try {
            $response = $this->sellerBankAccountRepository->changeStatus($id);
            
            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
