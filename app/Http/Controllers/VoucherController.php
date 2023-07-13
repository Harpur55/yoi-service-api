<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Repository\Voucher\VoucherRepositoryInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    protected $voucherRepository;

    public function __construct(VoucherRepositoryInterface $voucherRepository)
    {
        $this->voucherRepository = $voucherRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $response = $this->voucherRepository->getAll(request());

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVoucherRequest $request
     * @return JsonResponse
     */
    public function store(StoreVoucherRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->voucherRepository->create($request);

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
            $response = $this->voucherRepository->getById($id);

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
