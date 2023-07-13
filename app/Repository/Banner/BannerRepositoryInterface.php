<?php

namespace App\Repository\Banner;

interface BannerRepositoryInterface
{
    public function getAll();
    public function create($request);
}