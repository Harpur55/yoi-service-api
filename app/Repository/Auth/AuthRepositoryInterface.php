<?php

namespace App\Repository\Auth;

interface AuthRepositoryInterface
{
    public function register($request);
    public function login($request);
    public function logout($request);
    public function activate($request);
    public function resendActivation($request);
    public function forgotPassword($request);
    public function validateForgotPasswordCode($request);
    public function resetPassword($request);
}
