<?php

namespace App\Repository\ProductCategory;

interface ProductCategoryRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
    public function update($request);
}
