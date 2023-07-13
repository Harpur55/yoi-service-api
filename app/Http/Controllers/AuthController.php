<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Repository\Auth\AuthRepositoryInterface;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(StoreUserRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->authRepository->register($request);

            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                return $this->errorResponse($response->message, $response->data);
            }
        } catch (QueryException $exception) {
            DB::rollBack();

            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $response = $this->authRepository->login($request);

            if ($response->status) {
                DB::commit();

                return $this->successResponse($response->message, $response->data);
            } else {
                DB::rollBack();

                if (!isset($response->data)) {
                    return $this->unAuthorizedResponse();
                } else {
                    return $this->errorResponse($response->message, null);
                }
            }
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $response = $this->authRepository->logout($request);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->unAuthorizedResponse();
            }
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function activate(Request $request): JsonResponse
    {
        try {
            $response = $this->authRepository->activate($request);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function resendActivation(Request $request): JsonResponse
    {
        try {
            $response = $this->authRepository->resendActivation($request);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $exception) {
            return $this->serverErrorResponse($exception->getMessage());
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            DB::beginTransaction();

            $response = $this->authRepository->forgotPassword($request);

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

    public function validateForgotPasswordCode(Request $request)
    {
        try {
            $response = $this->authRepository->validateForgotPasswordCode($request);

            if ($response->status) {
                return $this->successResponse($response->message, $response->data);
            } else {
                return $this->errorResponse($response->message, $response->data);
            }
        } catch (Exception $e) {
            return $this->serverErrorResponse($e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $response = $this->authRepository->resetPassword($request);

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
