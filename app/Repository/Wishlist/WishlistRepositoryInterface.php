<?php

namespace App\Repository\Wishlist;

interface WishlistRepositoryInterface
{
    public function create($request);
    public function getByCustomerId();
    public function delete($id);
}
