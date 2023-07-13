<?php

namespace App\Repository\ProductReview;

interface ProductReviewRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
}
