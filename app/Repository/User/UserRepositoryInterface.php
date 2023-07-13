<?php

namespace App\Repository\User;

interface UserRepositoryInterface
{
    public function findUserLogin();
    public function update($request);
}
