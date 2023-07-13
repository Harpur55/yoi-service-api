<?php

namespace App\Repository\Seller;

use App\Http\Resources\ProductResource;
use App\Http\Resources\SellerProfileResource;
use App\Http\Resources\SellerResource;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellerApplicationNotificationSetting;
use App\Models\SellerEmailNotificationSetting;
use App\Models\SellerProfile;
use App\Models\User;
use App\Repository\SellerProfile\SellerProfileRepositoryInterface;
use App\Repository\Store\StoreRepositoryInterface;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class SellerRepository implements SellerRepositoryInterface
{
    use ServiceResponseHandler;

    protected
        $storeRepository, $sellerProfileRepository;

    public function __construct(
        StoreRepositoryInterface $storeRepository, SellerProfileRepositoryInterface $sellerProfileRepository
    )
    {
        $this->storeRepository = $storeRepository;
        $this->sellerProfileRepository = $sellerProfileRepository;
    }

    public function getAll($request): object
    {
        return $this->successResponse('Successfully fetch sellers', Seller::all());
    }

    public function getById(): object
    {
        $seller = Seller::with('store', 'code', 'profile')->where('user_id', Auth::id())->first();

        if (isset($seller)) {
            return $this->successResponse('Successfully fetch seller detail', new SellerResource($seller));
        } else {
            return $this->errorResponse('Seller not found', null);
        }
    }

    public function create($request): object
    {
        $user = User::find(Auth::id());

        $exist_seller = Seller::where('user_id', $user->id)->first();
        if ($exist_seller) {
            return $this->errorResponse('already registered as seller', null);
        }
        
        $validated_request = $request->validated();
        $validated_request['user_id'] = $user->id;
        $seller = Seller::create($validated_request);

        $input_store['seller_id'] = $seller->id;
        $this->storeRepository->create($input_store);

        $input_seller_profile['seller_id'] = $seller->id;
        $input_seller_profile['full_name'] = $validated_request['fullname'];
        $input_seller_profile['full_address'] = "";
        $input_seller_profile['photo_path'] = "";
        $input_seller_profile['latitude'] = 0;
        $input_seller_profile['longitude'] = 0;
        $input_seller_profile['phone'] = $validated_request['phone'];
        $input_seller_profile['slogan'] = "";
        $input_seller_profile['description'] = "";
        $input_seller_profile['store_name'] = $validated_request['store_name'];
        $this->sellerProfileRepository->create($input_seller_profile);

        $input_app_notif_setting['seller_id'] = $seller->id;
        $input_app_notif_setting['enable_all'] = 0;
        $input_app_notif_setting['new_order'] = 0;
        $input_app_notif_setting['in_progress_order'] = 0;
        $input_app_notif_setting['reject_order'] = 0;
        $input_app_notif_setting['finish_order'] = 0;
        $input_app_notif_setting['success_withdraw'] = 0;
        $input_app_notif_setting['fail_withdraw'] = 0;
        $input_app_notif_setting['promotion'] = 0;
        SellerApplicationNotificationSetting::create($input_app_notif_setting);

        $input_email_notif_setting['seller_id'] = $seller->id;
        $input_email_notif_setting['enable_all'] = 0;
        $input_email_notif_setting['order'] = 0;
        $input_email_notif_setting['withdraw'] = 0;
        $input_email_notif_setting['promotion'] = 0;
        SellerEmailNotificationSetting::create($input_email_notif_setting);

        return $this->successResponse('create seller success', $seller);
    }

    public function updateProfile($request)
    {
        $user = User::find(Auth::id());
        $seller = Seller::where('user_id', $user->id)->first();
        $seller_profile = SellerProfile::where('seller_id', $seller->id)->first();
        $seller_profile->full_name = $request->full_name ?? "";
        $seller_profile->full_address = $request->full_address ?? "";
        $seller_profile->slogan = $request->slogan ?? "";
        $seller_profile->description = $request->description ?? "";
        $seller_profile->store_name = $request->store_name ?? "";

        if ($request->file('picture') != null) {
            if (!empty($seller_profile->photo_path)) {
                RemoveImage($seller_profile->photo_path, 'seller');
            }

            $photo = ImageUploader($request->file('picture'), 'seller');

            $seller_profile->photo_path = $photo;
        }

        $seller_profile->save();

        return $this->successResponse('update profile success', new SellerProfileResource($seller_profile));
    }

    public function showDetailRateProduct($id)
    {
        $product = Product::with('category', 'detail', 'rate', 'store', 'likes', 'review')->where('id', $id)->first();

        return $this->successResponse('fetch success', new ProductResource($product));
    }
}
