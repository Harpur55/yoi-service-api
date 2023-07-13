<?php

namespace App\Repository\ApplicationRight;

use App\Http\Resources\ApplicationRightResource;
use App\Models\ApplicationRight;
use App\Traits\ServiceResponseHandler;

class ApplicationRightRepository implements ApplicationRightRepositoryInterface
{
    use ServiceResponseHandler;

    public function fetchAll()
    {
        return $this->successResponse('fetch success', ApplicationRightResource::collection(ApplicationRight::all()));
    }

    public function fetchById($id)
    {
        $data = ApplicationRight::find($id);

        if($data) {
            return $this->successResponse('fetch success', new ApplicationRightResource($data));
        } else {
            return $this->errorResponse('data not found', null);
        }
    }

    public function create($request)
    {
        $_request = $request->validated();

        $data = ApplicationRight::create($_request);
        
        return $this->successResponse('post success', new ApplicationRightResource($data));
    }

    public function update($request)
    {
        $_request = $request->validated();
        $data = ApplicationRight::find($_request['id']);

        if($data) {
            $data->title = $_request['title'];
            $data->content = $_request['content'];
            $data->save();

            return $this->successResponse('update success', new ApplicationRightResource($data));
        } else {
            return $this->errorResponse('data not found', null);
        }
    }
}