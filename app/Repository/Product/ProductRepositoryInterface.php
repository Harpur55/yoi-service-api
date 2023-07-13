<?php

namespace App\Repository\Product;

interface ProductRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
    public function update($request);
    public function fetchPopular($request);
    public function fetchBySeller();
    public function like($request);
    public function unlike($request);
    public function delete($id);

    public function fetchPopularForSeller($request);
}
