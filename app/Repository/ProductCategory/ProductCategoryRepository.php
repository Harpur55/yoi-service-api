<?php

namespace App\Repository\ProductCategory;

use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use App\Traits\ServiceResponseHandler;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    use ServiceResponseHandler;

    public function getAll($request): object
    {
        return $this->successResponse('fetch category success', ProductCategoryResource::collection(ProductCategory::paginate($request->query('limit'), ['*'], 'offset')));
    }

    public function getById($id): object
    {
        $product_category = ProductCategory::find($id);

        if (isset($product_category)) {
            return $this->successResponse('Successfully fetch product category', new ProductCategoryResource($product_category));
        } else {
            return $this->errorResponse('Product Category not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $icon = \IconUploader($request->file('icon'), 'category');
        
        $validated_request['icon_path'] = $icon;
        
        $product_category = ProductCategory::create($validated_request);

        return $this->successResponse('create category success', new ProductCategoryResource($product_category));
    }

    public function update($request): object
    {
        $validated_request = $request->validated();

        $data = ProductCategory::find($validated_request['id']);
        
        if ($data) {
            if($request->file('icon')) {
                $icon = \IconUploader($request->file('icon'), 'category');

                if($data->icon_path != "") {
                    \RemoveIcon($data->icon_path, 'category');
                }

                $data->icon_path = $icon;
            }
    
            if($request->file('detail_icon')) {
                $detail_icon = \IconUploader($request->file('detail_icon'), 'category');

                if($data->detail_icon != "") {
                    \RemoveIcon($data->detail_icon, 'category');
                }

                $data->detail_icon = $detail_icon;
            }

            $data->name = $validated_request['name'];
            $data->save();

            return $this->successResponse('update success', new ProductCategoryResource($data));
        }

        return $this->errorResponse('update fail', 'product category not found');
    }
}
