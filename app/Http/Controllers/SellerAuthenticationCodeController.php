<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSellerAuthenticationCodeRequest;
use App\Http\Requests\UpdateSellerAuthenticationCodeRequest;
use App\Repository\SellerAuthenticationCode\SellerAuthenticationCodeRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SellerAuthenticationCodeController extends Controller
{
    protected $sellerAuthenticationCodeRepository;

    public function __construct(SellerAuthenticationCodeRepositoryInterface $sellerAuthenticationCodeRepository)
    {
        $this->sellerAuthenticationCodeRepository = $sellerAuthenticationCodeRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSellerAuthenticationCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellerAuthenticationCodeRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->sellerAuthenticationCodeRepository->create($request);

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

    public function validateCode(StoreSellerAuthenticationCodeRequest $request)
    {
        try {
            $response = $this->sellerAuthenticationCodeRepository->validate($request);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->forbiddenResponse($response->message);
            }
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }
    
    public function update(UpdateSellerAuthenticationCodeRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->sellerAuthenticationCodeRepository->update($request);

            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                if ($response->message == 403) {
                    return $this->forbiddenResponse($response->message);
                } else {
                    return $this->errorResponse($response->message, $response->data);
                }
            }
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function show()
    {
        try {
            $response = $this->sellerAuthenticationCodeRepository->fetchBySeller();

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
