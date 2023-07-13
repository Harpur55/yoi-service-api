<?php

namespace App\Repository\Cart;

interface CartRepositoryInterface
{
    public function create($request);
    public function getByCustomerId();
    public function delete($id);
}
