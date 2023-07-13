<?php

use App\Http\Controllers\ApplicationRightController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CallbackCourierController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\CustomerApplicationNotificationSettingController;
use App\Http\Controllers\CustomerBankAccountController;
use App\Http\Controllers\CustomerEmailNotificationSettingController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\SellerApplicationNotificationSettingController;
use App\Http\Controllers\SellerAuthenticationCodeController;
use App\Http\Controllers\SellerBankAccountController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerEmailNotificationSettingController;
use App\Http\Controllers\StoreCourierShippingAddressController;
use App\Http\Controllers\StoreTermAndConditionController;
use App\Http\Controllers\SubDistrictController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\InternalAccess;
use App\Http\Middleware\SellerAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * v1 apis
 */
Route::prefix('v1')->group(function () {
    Route::get('not-authorized', function() {
        return response()->json([
            'status'    => false,
            'message'   => 'unauthorized',
            'data'      => null
        ], 401);
    })->name('auth/not-authorized');

    /**
     * auth apis
     */
    Route::prefix('auth')->group(function() {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('activate', [AuthController::class, 'activate']);
        Route::post('resend-activation', [AuthController::class, 'resendActivation']);
        
        Route::prefix('password')->group(function() {
            Route::prefix('reset')->group(function () {
                Route::post('request', [AuthController::class, 'forgotPassword']);
                Route::post('validate', [AuthController::class, 'validateForgotPasswordCode']);

                Route::post('/', [AuthController::class, 'resetPassword']);
            });
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    /**
     * authorized apis
     */
    Route::middleware('auth:sanctum')->group(function() {
        /**
         * user apis
         */
        Route::prefix('user')->group(function () {
            Route::post('update', [UserController::class, 'update']);
            Route::get('find', [UserController::class, 'show']);

            /**
             * seller apis
             */
            Route::prefix('seller')->group(function () {
                Route::post('/', [SellerController::class, 'store']);
                Route::get('/', [SellerController::class, 'show']);
            });
        });

        /**
         * customer apis
         */
        Route::prefix('customer')->group(function () {
            /**
             * cart apis
             */
            Route::prefix('cart')->group(function () {
                Route::get('/', [CartController::class, 'show']);
                Route::post('/', [CartController::class, 'store']);
                Route::delete('/{id}', [CartController::class, 'destroy']);
            });
            
            /**
             * wishlist apis
             */
            Route::prefix('wishlist')->group(function () {
                Route::get('/', [WishlistController::class, 'show']);
                Route::post('/', [WishlistController::class, 'store']);
                Route::delete('/{id}', [WishlistController::class, 'destroy']);
            });

            /**
             * notif apis
             */
            Route::prefix('notif')->group(function () {
                Route::prefix('email')->group(function () {
                    Route::get('/', [CustomerEmailNotificationSettingController::class, 'show']);
                    Route::put('/', [CustomerEmailNotificationSettingController::class, 'update']);
                });

                Route::prefix('app')->group(function () {
                    Route::get('/', [CustomerApplicationNotificationSettingController::class, 'show']);
                    Route::put('/', [CustomerApplicationNotificationSettingController::class, 'update']);
                });
            });

            /**
             * shipping apis
             */
            Route::prefix('shipping')->group(function () {
                Route::prefix('address')->group(function () {
                    Route::get('/', [CustomerAddressController::class, 'index']);
                    Route::post('/', [CustomerAddressController::class, 'store']);
                    Route::post('/{id}', [CustomerAddressController::class, 'changeStatus']);
                });
            });

            /**
             * order apis
             */
            Route::prefix('order')->group(function () {
                Route::get('/', [OrderController::class, 'index']);
                Route::get('/calculate', [OrderController::class, 'calculate']);
                Route::get('/init', [OrderController::class, 'init']);
                Route::get('/direct/{id}', [OrderController::class, 'directOrder']);
                Route::get('/{id}', [OrderController::class, 'show']);
                
                /**
                 * order payment apis
                 */
                Route::prefix('payment')->group(function () {
                    Route::post('/', [OrderController::class, 'store']);
                });
            });

            /**
             * product apis
             */
            Route::prefix('product')->group(function () {
                Route::post('like', [ProductController::class, 'like']);
                Route::post('unlike', [ProductController::class, 'unlike']);
            });

            /**
             * province apis
             */
            Route::prefix('province')->group(function () {
                Route::get('/', [ProvinceController::class, 'index']);
            });

            /**
             * city apis
             */
            Route::prefix('city')->group(function () {
                Route::get('/', [CityController::class, 'index']);
            });

            /**
             * district apis
             */
            Route::prefix('district')->group(function () {
                Route::get('/', [DistrictController::class, 'index']);

                /**
                 * sub district apis
                 */
                Route::prefix('sub')->group(function () {
                    Route::get('/', [SubDistrictController::class, 'index']);
                });
            });

            /**
             * bank account apis
             */
            Route::prefix('bank-account')->group(function () {
                Route::get('/', [CustomerBankAccountController::class, 'index']);
                Route::post('/', [CustomerBankAccountController::class, 'store']);
                Route::post('/{id}', [CustomerBankAccountController::class, 'changeStatus']);
            });
        });

        /**
         * seller apis
         */
        Route::middleware([SellerAccess::class])->group(function () {
            Route::prefix('seller')->group(function () {
                Route::post('/update-profile', [SellerController::class, 'updateProfile']);
    
                /**
                 * shipping apis
                 */
                Route::prefix('shipping')->group(function () {
                    Route::prefix('courier')->group(function () {
                        Route::get('/', [StoreCourierShippingAddressController::class, 'index']);
                        Route::post('/', [StoreCourierShippingAddressController::class, 'store']);
                        Route::post('/{id}', [StoreCourierShippingAddressController::class, 'changeStatus']);

                    });
                });

                /**
                 * tac apis
                 */
                Route::prefix('tac')->group(function () {
                    Route::get('/', [StoreTermAndConditionController::class, 'index']);
                    Route::post('/', [StoreTermAndConditionController::class, 'store']);
                });

                /**
                 * product apis
                 */
                Route::prefix('product')->group(function () {
                    Route::get('popular', [ProductController::class, 'fetchPopularForSeller']);
                    
                    Route::post('/update', [ProductController::class, 'update']);
                    Route::post('/', [ProductController::class, 'store']);
                    Route::get('/', [ProductController::class, 'fetchBySeller']);
                    Route::get('/{id}', [ProductController::class, 'show']);

                    Route::delete('/{id}', [ProductController::class, 'delete']);

                    Route::get('/review/{id}', [SellerController::class, 'showDetailRateProduct']);
                });

                /**
                 * code apis
                 */
                Route::prefix('code')->group(function () {
                    Route::post('/', [SellerAuthenticationCodeController::class, 'store']);
                    Route::post('validate', [SellerAuthenticationCodeController::class, 'validateCode']);
                    Route::post('/update', [SellerAuthenticationCodeController::class, 'update']);
                    Route::get('/', [SellerAuthenticationCodeController::class, 'show']);
                });

                /**
                 * notif apis
                 */
                Route::prefix('notif')->group(function () {
                    Route::prefix('email')->group(function () {
                        Route::get('/', [SellerEmailNotificationSettingController::class, 'show']);
                        Route::put('/', [SellerEmailNotificationSettingController::class, 'update']);
                    });

                    Route::prefix('app')->group(function () {
                        Route::get('/', [SellerApplicationNotificationSettingController::class, 'show']);
                        Route::put('/', [SellerApplicationNotificationSettingController::class, 'update']);
                    });
                });

                /**
                 * province apis
                 */
                Route::prefix('province')->group(function () {
                    Route::get('/', [ProvinceController::class, 'index']);
                });

                /**
                 * city apis
                 */
                Route::prefix('city')->group(function () {
                    Route::get('/', [CityController::class, 'index']);
                });

                /**
                 * district apis
                 */
                Route::prefix('district')->group(function () {
                    Route::get('/', [DistrictController::class, 'index']);

                    /**
                     * sub district apis
                     */
                    Route::prefix('sub')->group(function () {
                        Route::get('/', [SubDistrictController::class, 'index']);
                    });
                });

                Route::prefix('order')->group(function () {
                    Route::get('/', [OrderController::class, 'fetchBySeller']);
                    Route::get('/cancel/{id}', [OrderController::class, 'cancelOrder']);
                    Route::get('/cancel', [OrderController::class, 'fetchBySellerForCancelled']);
                    Route::get('/approved', [OrderController::class, 'fetchBySellerForIsApproved']);
                    Route::get('/approve/{id}', [OrderController::class, 'approveOrder']);

                    Route::get('/shipping', [OrderController::class, 'fetchBySellerForIsWaitingShipping']);
                    Route::get('/pickup/{id}', [OrderController::class, 'requestPickup']);
                });

                /**
             * bank account apis
             */
            Route::prefix('bank-account')->group(function () {
                Route::get('/', [SellerBankAccountController::class, 'index']);
                Route::post('/', [SellerBankAccountController::class, 'store']);
                Route::post('/{id}', [SellerBankAccountController::class, 'changeStatus']);
            });
            });
        });
    });

    /**
     * internal access apis
     */
    Route::middleware([InternalAccess::class])->group(function() {
       Route::prefix('internal')->group(function () {
        /**
         * product apis
         */
        Route::prefix('product')->group(function () {
            Route::prefix('category')->group(function () {
                Route::get('/', [ProductCategoryController::class, 'index']);
                Route::post('/', [ProductCategoryController::class, 'store']);
                Route::put('/', [ProductCategoryController::class, 'update']);
            });

            Route::prefix('popular')->group(function () {
                Route::get('/', [ProductController::class, 'fetchPopular']);
            });

            Route::get('/', [ProductController::class, 'index']);
            Route::get('/{id}', [ProductController::class, 'show']);
        });
    
        /**
         * banner apis
         */
        Route::prefix('banner')->group(function () {
            Route::get('/', [BannerController::class, 'index']);
            Route::post('/', [BannerController::class, 'store']);
        });

        /**
         * application right
         */
        Route::prefix('app-right')->group(function () {
            Route::get('/send-notif', [ApplicationRightController::class, 'sendNotif']);

            Route::get('/', [ApplicationRightController::class, 'index']);
            Route::get('/{id}', [ApplicationRightController::class, 'show']);
            Route::post('/', [ApplicationRightController::class, 'store']);
            Route::put('/', [ApplicationRightController::class, 'update']);
        });
       });
    });

    /**
     * UnAuthorized API
     *
     * All APIs considered to be used even without Access Token
     */
    Route::prefix('product')->group(function () {
        Route::resource('category', ProductCategoryController::class);
        Route::resource('detail', ProductDetailController::class);
        Route::resource('review', ProductReviewController::class);
        Route::resource('rate', ProductRateController::class);

        Route::get('popular', [ProductController::class, 'mostPopular']);
    });
    Route::resource('product', ProductController::class);

    /**
     * order payment apis
     */
    Route::prefix('payment')->group(function () {
        Route::post('callback', [OrderPaymentController::class, 'callback']);
    });

    /**
     * bank account apis
     */
    Route::prefix('bank-account')->group(function () {
        Route::get('/', [BankAccountController::class, 'index']);
        Route::post('/', [BankAccountController::class, 'store']);
    });
});

Route::post('callback-courier', [CallbackCourierController::class, 'callback']);
