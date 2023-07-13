<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerBankAccountRequest;
use App\Http\Requests\UpdateCustomerBankAccountRequest;
use App\Models\CustomerBankAccount;
use App\Repository\CustomerBankAccount\CustomerBankAccountRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CustomerBankAccountController extends Controller
{
    protected $customerBankAccountRepository;

    public function __construct(CustomerBankAccountRepositoryInterface $customerBankAccountRepository)
    {
        $this->customerBankAccountRepository = $customerBankAccountRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $response = $this->customerBankAccountRepository->getByCustomer();

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCustomerBankAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerBankAccountRequest $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->customerBankAccountRepository->create($request);

            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $e) {
            DB::rollBack();

            return $this->serverErrorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerBankAccount  $customerBankAccount
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerBankAccount $customerBankAccount)
    {
        //
    }

    public function changeStatus($id)
    {
        try {
            $response = $this->customerBankAccountRepository->changeStatus($id);
            
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
