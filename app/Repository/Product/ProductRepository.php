<?php

namespace App\Repository\Product;

use App\Http\Resources\ProductResource;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Seller;
use App\Models\Store;
use App\Models\Wishlist;
use App\Models\WishlistDetail;
use App\Repository\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repository\ProductDetail\ProductDetailRepositoryInterface;
use App\Repository\ProductRate\ProductRateRepositoryInterface;
use App\Traits\ServiceResponseHandler;
use Illuminate\Support\Facades\Auth;

class ProductRepository implements ProductRepositoryInterface
{
    use ServiceResponseHandler;

    protected $productDetailRepository;
    protected $productCategoryRepository;
    protected $productRateRepository;

    public function __construct(
        ProductDetailRepositoryInterface $productDetailRepository,
        ProductCategoryRepositoryInterface $productCategoryRepository,
        ProductRateRepositoryInterface $productRateRepository
    )
    {
        $this->productDetailRepository = $productDetailRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->productRateRepository = $productRateRepository;
    }

    public function getAll($request): object
    {
        $keyword = null;
        $category = null;

        if ($request->filled('keyword')) {
            $keyword = $request->query('keyword');
        }

        if ($request->filled('category')) {
            $category = $request->query('category');
        }

        $products = Product::with('category', 'detail', 'rate', 'store', 'likes')
                            ->whereHas('detail', function ($query) use ($keyword) {
                                $query->when($keyword, function ($query_keyword, $keyword) {
                                    return $query_keyword->where('name', 'LIKE', '%'.$keyword.'%');
                                });
                            })
                            ->when($category, function ($query) use ($category) {
                                $query->where('product_category_id', $category);
                            })
                            ->where('deleted', false)
                            ->where('is_active', TRUE)
                            ->orderBy('id', 'desc')
                            ->paginate($request->query('limit'), ['*'], 'offset');

        return $this->successResponse('fetch product success', ProductResource::collection($products));
    }

    public function getById($id): object
    {
        $product = Product::where('id', $id)->where('deleted', false)->first();

        if (isset($product)) {
            return $this->successResponse('fetch product by id success', new ProductResource($product));
        } else {
            return $this->errorResponse('product not found', null);
        }
    }

    public function create($request): object
    {
        $validated_request = $request->validated();

        $response_category_detail = $this->productCategoryRepository->getById($validated_request['product_category_id']);
        if ($response_category_detail->status) {
            $seller = Seller::where('user_id', Auth::id())->first();
            $store = Store::where('seller_id', $seller->id)->first();

            $validated_request['store_id'] = $store->id;

            $product = Product::create($validated_request);

            if ($request->file('picture') != null) {
                $image = ImageUploader($request->file('picture'), 'product');
            } else {
                return $this->errorResponse('image cannot empty', null);
            }

            $request_product_detail = [
                'product_id'            => $product->id,
                'name'                  => $validated_request['name'],
                'price'                 => $validated_request['price'],
                'discount'              => $validated_request['discount'],
                'keyword'               => $validated_request['keyword'] ?? "",
                'description'           => $validated_request['description'],
                'sku'                   => $validated_request['sku'] ?? "",
                'stock_status'          => $validated_request['stock_status'] ?? 0,
                'selected_service'      => $validated_request['selected_service'] ?? "",
                'selected_size'         => $validated_request['selected_size'] ?? "",
                'selected_color'        => $validated_request['selected_color'] ?? "",
                'weight'                => $validated_request['weight'] ?? 0.0,
                'long'                  => $validated_request['long'] ?? 0.0,
                'wide'                  => $validated_request['wide'] ?? 0.0,
                'tall'                  => $validated_request['tall'] ?? 0.0,
                'visibility'            => $validated_request['visibility'] ?? 0,
                'additional_note'       => $validated_request['additional_note'] ?? "",
                'show_additional_note'  => $validated_request['show_additional_note'] ?? 0,
                'image'                 => $image
            ];

            $this->productDetailRepository->create($request_product_detail);

            $request_product_rate = [
                'product_id'    => $product->id,
                'total'         => 0.0
            ];

            $this->productRateRepository->create($request_product_rate);

            return $this->successResponse('create product success', new ProductResource($product));
        } else {
            return $response_category_detail;
        }
    }

    public function update($request): object
    {
        $product = Product::find($request->id);
        $prod_detail = ProductDetail::where('product_id', $product->id)->first();

        if ($request->file('picture') != null) {
            $image = ImageUploader($request->file('picture'), 'product');
            $prod_detail->image = $image;
        }

        $prod_detail->name = $request['name'];
        $prod_detail->price = $request['price'];
        $prod_detail->discount = $request['discount'];
        $prod_detail->keyword = $request['keyword'] ?? "";
        $prod_detail->description = $request['description'];
        $prod_detail->sku = $request['sku']  ?? "";
        $prod_detail->selected_service = $request['selected_service'];
        $prod_detail->selected_color = $request['selected_color'];
        $prod_detail->weight = $request['weight'];
        $prod_detail->long = $request['long'];
        $prod_detail->wide = $request['wide'];
        $prod_detail->tall = $request['tall'];
        $prod_detail->visibility = $request['visibility'];
        $prod_detail->additional_note = $request['additional_note'];
        $prod_detail->show_additional_note = $request['show_additional_note'];
        $prod_detail->save();

        return $this->successResponse('update product success', new ProductResource($product));
    }

    public function fetchPopular($request): object
    {
        $products = Product::with('detail', 'rate', 'likes')->whereHas('rate', function ($query) {
                                return $query->orderBy('updated_at', 'desc');
                            })
                            ->where('deleted', false)
                            ->paginate($request->query('limit'), ['*'], 'offset');

        return $this->successResponse('fetch popular product success', ProductResource::collection($products));
    }

    public function fetchBySeller(): object
    {
        $keyword = null;

        if (request()->filled('keyword')) {
            $keyword = request()->query('keyword');
        }

        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();

        $products = Product::with('detail', 'rate')
                            ->where('store_id', $store->id)
                            ->where('deleted', false)
                            ->whereHas('detail', function ($query) use ($keyword) {
                                $query->when($keyword, function ($query_keyword, $keyword) {
                                    return $query_keyword->where('name', 'LIKE', '%'.$keyword.'%');
                                });
                            })
                            ->orderBy('id', 'DESC')
                            ->paginate(request()->query('limit'), ['*'], 'offset');

        return $this->successResponse('fetch product success', ProductResource::collection($products));
    }

    public function like($request)
    {
        $product = Product::find($request->product_id);
        $product->like(Auth::id());
        $product->save();

        $customer = Customer::where('user_id', Auth::id())->first();
        $request_wishlist = [
            'product_id'        => $request->product_id,
            'customer_id'       => $customer->id
        ];

        $wishlist = Wishlist::create($request_wishlist);

        $product = Product::with('detail')->where('id', $wishlist->product_id)->first();

        $request_wishlist_detail = [
            'wishlist_id'           => $wishlist->id,
            'store_name'            => $product->store->seller->profile->full_name,
            'amount'                => 1,
            'price'                 => $product->detail->price,
            'discount'              => 0.0,
            'selected_size'         => "S",
            'selected_color'        => "red"
        ];

        WishlistDetail::create($request_wishlist_detail);

        return $this->successResponse('like product success', $product);
    }

    public function unlike($request)
    {
        $product = Product::find($request->product_id);
        $product->unlike(Auth::id());
        $product->save();

        $customer = Customer::where('user_id', Auth::id())->first();
        $wishlist = Wishlist::where('customer_id', $customer->id)->where('product_id', $request->product_id)->first();

        if ($wishlist) {
            $wishlist->detail()->delete();
            $wishlist->delete();
        }
        
        return $this->successResponse('unlike product success', $product);
    }

    public function fetchPopularForSeller($request): object
    {
        $seller = Seller::where('user_id', Auth::id())->first();
        $store = Store::where('seller_id', $seller->id)->first();

        $products = Product::with('detail', 'rate', 'likes')->where('deleted', false)->whereHas('rate', function ($query) {
            return $query->orderBy('total', 'desc');
        })
        ->where('store_id', $store->id)
        ->paginate($request->query('limit'), ['*'], 'offset');

        return $this->successResponse('fetch popular product success', ProductResource::collection($products));
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $product->deleted = true;
        $product->save();

        return $this->successResponse('deleted success', null);
    }
}
