<?php

namespace App\Repository\CustomerProfile;

interface CustomerProfileRepositoryInterface
{
    public function getAll($request);
    public function getById($id);
    public function create($request);
    public function update($request, $id);
    public function delete($id);
}
