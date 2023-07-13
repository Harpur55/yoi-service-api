<?php

namespace App\Repository\Banner;

use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Traits\ServiceResponseHandler;

class BannerRepository implements BannerRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll()
    {
        return $this->successResponse('fetch banner success', BannerResource::collection(Banner::all()));
    }

    public function create($request)
    {
        if ($request->file('image') != null) {
            $image = ImageUploader($request->file('image'), 'banner');

            $request_banner = [
                'image' => $image
            ];

            $banner = Banner::create($request_banner);

            return $this->successResponse('create banner success', new BannerResource($banner));
        } else {
            return $this->errorResponse('image cannot be empty', null);
        }
    }
}