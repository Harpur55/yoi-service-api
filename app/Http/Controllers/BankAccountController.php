<?php

namespace App\Http\Controllers;

use App\Repository\BankAccount\BankAccountRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    protected $bankAccountRepository;

    public function __construct(BankAccountRepositoryInterface $bankAccountRepository)
    {
        $this->bankAccountRepository = $bankAccountRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $response = $this->bankAccountRepository->get();

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $response = $this->bankAccountRepository->create($request);

            return $this->successResponse($response->message, $response->data);
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }
}
