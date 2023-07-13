<?php

namespace App\Repository\User;

use App\Http\Resources\UserResource;
use App\Models\CustomerProfile;
use App\Models\User;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    use ServiceResponseHandler;

    public function findUserLogin(): object
    {
        $user = request()->user();

        if (isset($user)) {
            return $this->successResponse('user found', $user);
        } else {
            return $this->errorResponse('user not found', null);
        }
    }

    public function update($request): object
    {
        $validated_request = $request->validated();

        $user = User::find(Auth::id());

        $user->first_name = $validated_request['first_name'];
        $user->last_name = $validated_request['last_name'];

        $user->name = $validated_request['first_name'] . " " . $validated_request['last_name'];

        $customer_profile = CustomerProfile::find($user->customer->profile->id);
        $customer_profile->first_name = $user->first_name;
        $customer_profile->last_name = $user->last_name;

        if ($request->file('picture') != null) {
            if (!empty($customer_profile->photo_path)) {
                RemoveImage($customer_profile->photo_path, 'profile');
            }

            $photo = ImageUploader($request->file('picture'), 'profile');

            $customer_profile->photo_path = $photo;
        }

        $customer_profile->save();
        $user->save();
        
        return $this->successResponse('update success', new UserResource(User::find(Auth::id())));

    }
}
