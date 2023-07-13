<?php

namespace App\Repository\ApplicationRight;

interface ApplicationRightRepositoryInterface
{
    public function fetchAll();
    public function fetchById($id);
    public function create($request);
    public function update($request);
}