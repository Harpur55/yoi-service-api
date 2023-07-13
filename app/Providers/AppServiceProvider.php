<?php

namespace App\Providers;

use App\Repository\ApplicationRight\ApplicationRightRepository;
use App\Repository\ApplicationRight\ApplicationRightRepositoryInterface;
use App\Repository\Customer\CustomerRepository;
use App\Repository\Customer\CustomerRepositoryInterface;
use App\Repository\Auth\AuthRepository;
use App\Repository\Auth\AuthRepositoryInterface;
use App\Repository\BankAccount\BankAccountRepository;
use App\Repository\BankAccount\BankAccountRepositoryInterface;
use App\Repository\Banner\BannerRepository;
use App\Repository\Banner\BannerRepositoryInterface;
use App\Repository\Cart\CartRepository;
use App\Repository\Cart\CartRepositoryInterface;
use App\Repository\CartDetail\CartDetailRepository;
use App\Repository\CartDetail\CartDetailRepositoryInterface;
use App\Repository\CustomerAddress\CustomerAddressRepository;
use App\Repository\CustomerAddress\CustomerAddressRepositoryInterface;
use App\Repository\CustomerApplicationNotificationSetting\CustomerApplicationNotificationSettingRepository;
use App\Repository\CustomerApplicationNotificationSetting\CustomerApplicationNotificationSettingRepositoryInterface;
use App\Repository\CustomerBankAccount\CustomerBankAccountRepository;
use App\Repository\CustomerBankAccount\CustomerBankAccountRepositoryInterface;
use App\Repository\CustomerEmailNotificationSetting\CustomerEmailNotificationSettingRepository;
use App\Repository\CustomerEmailNotificationSetting\CustomerEmailNotificationSettingRepositoryInterface;
use App\Repository\CustomerNotification\CustomerNotificationRepository;
use App\Repository\CustomerNotification\CustomerNotificationRepositoryInterface;
use App\Repository\CustomerProfile\CustomerProfileRepository;
use App\Repository\CustomerProfile\CustomerProfileRepositoryInterface;
use App\Repository\Order\OrderRepository;
use App\Repository\Order\OrderRepositoryInterface;
use App\Repository\OrderDetail\OrderDetailRepository;
use App\Repository\OrderDetail\OrderDetailRepositoryInterface;
use App\Repository\OrderPayment\OrderPaymentRepository;
use App\Repository\OrderPayment\OrderPaymentRepositoryInterface;
use App\Repository\OrderShipping\OrderShippingRepository;
use App\Repository\OrderShipping\OrderShippingRepositoryInterface;
use App\Repository\Product\ProductRepository;
use App\Repository\Product\ProductRepositoryInterface;
use App\Repository\ProductAttribute\ProductAttributeRepository;
use App\Repository\ProductAttribute\ProductAttributeRepositoryInterface;
use App\Repository\ProductAttributeDetail\ProductAttributeDetailRepository;
use App\Repository\ProductAttributeDetail\ProductAttributeDetailRepositoryInterface;
use App\Repository\ProductCategory\ProductCategoryRepository;
use App\Repository\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repository\ProductDetail\ProductDetailRepository;
use App\Repository\ProductDetail\ProductDetailRepositoryInterface;
use App\Repository\ProductRate\ProductRateRepository;
use App\Repository\ProductRate\ProductRateRepositoryInterface;
use App\Repository\ProductReview\ProductReviewRepository;
use App\Repository\ProductReview\ProductReviewRepositoryInterface;
use App\Repository\Seller\SellerRepository;
use App\Repository\Seller\SellerRepositoryInterface;
use App\Repository\SellerApplicationNotificationSetting\SellerApplicationNotificationSettingRepository;
use App\Repository\SellerApplicationNotificationSetting\SellerApplicationNotificationSettingRepositoryInterface;
use App\Repository\SellerAuthenticationCode\SellerAuthenticationCodeRepository;
use App\Repository\SellerAuthenticationCode\SellerAuthenticationCodeRepositoryInterface;
use App\Repository\SellerBankAccount\SellerBankAccountRepository;
use App\Repository\SellerBankAccount\SellerBankAccountRepositoryInterface;
use App\Repository\SellerEmailNotificationSetting\SellerEmailNotificationSettingRepository;
use App\Repository\SellerEmailNotificationSetting\SellerEmailNotificationSettingRepositoryInterface;
use App\Repository\SellerNotification\SellerNotificationRepository;
use App\Repository\SellerNotification\SellerNotificationRepositoryInterface;
use App\Repository\SellerProfile\SellerProfileRepository;
use App\Repository\SellerProfile\SellerProfileRepositoryInterface;
use App\Repository\Store\StoreRepository;
use App\Repository\Store\StoreRepositoryInterface;
use App\Repository\StoreCourierShippingAddress\StoreCourierShippingAddressRepository;
use App\Repository\StoreCourierShippingAddress\StoreCourierShippingAddressRepositoryInterface;
use App\Repository\StoreTermAndCondition\StoreTermAndConditionRepository;
use App\Repository\StoreTermAndCondition\StoreTermAndConditionRepositoryInterface;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRepositoryInterface;
use App\Repository\Wishlist\WishlistRepository;
use App\Repository\Wishlist\WishlistRepositoryInterface;
use App\Repository\WishlistDetail\WishlistDetailRepository;
use App\Repository\WishlistDetail\WishlistDetailRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(CustomerProfileRepositoryInterface::class, CustomerProfileRepository::class);
        $this->app->bind(CustomerAddressRepositoryInterface::class, CustomerAddressRepository::class);
        $this->app->bind(CustomerBankAccountRepositoryInterface::class, CustomerBankAccountRepository::class);
        $this->app->bind(CustomerApplicationNotificationSettingRepositoryInterface::class, CustomerApplicationNotificationSettingRepository::class);
        $this->app->bind(CustomerEmailNotificationSettingRepositoryInterface::class, CustomerEmailNotificationSettingRepository::class);
        $this->app->bind(CustomerNotificationRepositoryInterface::class, CustomerNotificationRepository::class);
        $this->app->bind(SellerRepositoryInterface::class, SellerRepository::class);
        $this->app->bind(SellerAuthenticationCodeRepositoryInterface::class, SellerAuthenticationCodeRepository::class);
        $this->app->bind(SellerBankAccountRepositoryInterface::class, SellerBankAccountRepository::class);
        $this->app->bind(SellerApplicationNotificationSettingRepositoryInterface::class, SellerApplicationNotificationSettingRepository::class);
        $this->app->bind(SellerEmailNotificationSettingRepositoryInterface::class, SellerEmailNotificationSettingRepository::class);
        $this->app->bind(SellerNotificationRepositoryInterface::class, SellerNotificationRepository::class);
        $this->app->bind(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->bind(StoreCourierShippingAddressRepositoryInterface::class, StoreCourierShippingAddressRepository::class);
        $this->app->bind(StoreTermAndConditionRepositoryInterface::class, StoreTermAndConditionRepository::class);
        $this->app->bind(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductDetailRepositoryInterface::class, ProductDetailRepository::class);
        $this->app->bind(ProductAttributeRepositoryInterface::class, ProductAttributeRepository::class);
        $this->app->bind(ProductAttributeDetailRepositoryInterface::class, ProductAttributeDetailRepository::class);
        $this->app->bind(ProductRateRepositoryInterface::class, ProductRateRepository::class);
        $this->app->bind(ProductReviewRepositoryInterface::class, ProductReviewRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(CartDetailRepositoryInterface::class, CartDetailRepository::class);
        $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);
        $this->app->bind(WishlistDetailRepositoryInterface::class, WishlistDetailRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderDetailRepositoryInterface::class, OrderDetailRepository::class);
        $this->app->bind(OrderPaymentRepositoryInterface::class, OrderPaymentRepository::class);
        $this->app->bind(BannerRepositoryInterface::class, BannerRepository::class);
        $this->app->bind(SellerProfileRepositoryInterface::class, SellerProfileRepository::class);
        $this->app->bind(OrderShippingRepositoryInterface::class, OrderShippingRepository::class);
        $this->app->bind(BankAccountRepositoryInterface::class, BankAccountRepository::class);
        $this->app->bind(ApplicationRightRepositoryInterface::class, ApplicationRightRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
    }
}
