<?php

namespace App\Repository\Auth;

use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\CustomerApplicationNotificationSetting;
use App\Models\CustomerEmailNotificationSetting;
use App\Models\CustomerProfile;
use App\Models\ResetPassword;
use App\Models\User;
use App\Models\UserActivation;
use App\Traits\ServiceResponseHandler;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use MikeMcLin\WpPassword\Facades\WpPassword;

class AuthRepository implements AuthRepositoryInterface
{
    use ServiceResponseHandler;

    public function register($request): object
    {
        try {
            $validated_request = $request->validated();

            $exist_data = User::where('email', $validated_request['email'])->first();
            if ($exist_data) {
                $is_active = UserActivation::where('user_id', $exist_data->id)->first();
                if ($is_active && $is_active->is_activated == TRUE) {
                    return $this->errorResponse('already registered', null);
                }
            }

            $validated_request['name'] = $validated_request['first_name'] . ' ' . $validated_request['last_name'];
            $validated_request['password'] = WpPassword::make($validated_request['password']);
            $validated_request['type'] = 'customer';

            $user = User::create($validated_request);

            $input_customer['user_id'] = $user->id;
            $customer = Customer::create($input_customer);

            $input_customer_profile['customer_id'] = $customer->id;
            $input_customer_profile['first_name'] = $user->first_name;
            $input_customer_profile['last_name'] = $user->last_name;
            $input_customer_profile['photo_path'] = "";
            CustomerProfile::create($input_customer_profile);

            $input_user_activation['activation_code'] = RandNumberGenerator(4);
            $input_user_activation['user_id'] = $user->id;
            $input_user_activation['is_activated'] = false;
            $user_activation = UserActivation::create($input_user_activation);

            $input_customer_email_notif_setting['customer_id'] = $customer->id;
            $input_customer_email_notif_setting['enable_all'] = 0;
            $input_customer_email_notif_setting['order_status'] = 0;
            $input_customer_email_notif_setting['seller_information'] = 0;
            CustomerEmailNotificationSetting::create($input_customer_email_notif_setting);

            $input_customer_app_notif_setting['customer_id'] = $customer->id;
            $input_customer_app_notif_setting['enable_all'] = 0;
            $input_customer_app_notif_setting['order_status'] = 0;
            $input_customer_app_notif_setting['chat'] = 0;
            $input_customer_app_notif_setting['promotion'] = 0;
            CustomerApplicationNotificationSetting::create($input_customer_app_notif_setting);

            Http::post(env('MAIL_SERVICE_HOST') . '/v1/send/user-activation', [
                'sub'   => $user->name,
                'to'    => $user->email,
                'code'  => $user_activation->activation_code
            ]);

            return $this->successResponse('register success', $user);
        } catch (QueryException $exception) {
            return $this->errorResponse($exception->getMessage(), null);
        }
    }

    public function login($request): object
    {
        try {
            $user = User::where('email', $request['email'])->first();

            if (!$user) return $this->unAuthorizedResponse();

            if (WpPassword::check($request['password'], $user->password)) {
                if ($user->activation->is_activated == true) {
                    $token = $user->createToken($user->name)->plainTextToken;;

                    return $this->successResponse('login success', [
                        'user'      => new UserResource($user),
                        'token'     => $token
                    ]);
                } else {
                    return $this->errorResponse('inactive user', $user);
                }
            } else {
                return $this->unAuthorizedResponse();
            }
        } catch (Exception $exception) {
            return $this->errorResponse('login failed', $exception->getMessage());
        }
    }

    public function logout($request): object
    {
        try {
            $user = $request->user();

            if (isset($user)) {
                $user->currentAccessToken()->delete();

                return $this->successResponse('logout success', null);
            } else {
                return $this->unAuthorizedResponse();
            }
        } catch (Exception $exception) {
            return $this->errorResponse('Logout Failed', $exception->getMessage());
        }
    }

    public function activate($request): object
    {
        try {
            $user = User::find($request['user_id']);
            
            if ($user) {
                $user_activation = UserActivation::where('activation_code', $request['activation_code'])->where('is_activated', false)->where('user_id', $user->id)->first();

                if ($user_activation) {
                    $user_activation->is_activated = true;
                    $user_activation->save();

                    $another_user = User::where('email', $user->email)->where('id', '!=', $user->id)->get();

                    foreach($another_user as $_user) {
                        if ($_user->customer) {
                            $_user->customer->profile()->delete();
                            $_user->customer->emailNotif()->delete();
                            $_user->customer->appNotif()->delete();
                            $_user->customer->address()->delete();
                            $_user->customer()->delete();
                        }
                        
                        $_user->activation()->delete();
                        $_user->delete();
                    }

                    return $this->successResponse('activate success', $user);
                } else {
                    return $this->errorResponse('invalid code', null);
                }
            } else {
                return $this->errorResponse('user not found', null);
            }
        } catch (Exception $exception) {
            return $this->errorResponse('activate failed', $exception->getMessage());
        }
    }

    public function resendActivation($request)
    {
        $user = User::find($request->id);
        if ($user) {
            if ($user->activation->resend_counter < $user->activation->max_resend) {
                $user->activation->activation_code = RandNumberGenerator(4);
                $user->activation->resend_counter = $user->activation->resend_counter + 1;
                $user->activation->save();

                Http::post(env('MAIL_SERVICE_HOST') . '/v1/send/user-activation', [
                    'sub'   => $user->name,
                    'to'    => $user->email,
                    'code'  => $user->activation->activation_code
                ]);

                $resp = (object) [
                    'resend_counter'    => $user->activation->resend_counter,
                    'max_resend'        => $user->activation->max_resend,
                    'user'              => $user
                ];

                return $this->successResponse('success resend', $resp);
            } else {
                if ($user->activation->resend_avail_at) {
                    if (Carbon::now()->gt($user->activation->resend_avail_at)) {
                        $user->activation->resend_avail_at = null;
                        $user->activation->activation_code = RandNumberGenerator(4);
                        $user->activation->resend_counter = 1;
                        $user->activation->save();

                        Http::post(env('MAIL_SERVICE_HOST') . '/v1/send/user-activation', [
                            'sub'   => $user->name,
                            'to'    => $user->email,
                            'code'  => $user->activation->activation_code
                        ]);
        
                        $resp = (object) [
                            'resend_counter'    => $user->activation->resend_counter,
                            'max_resend'        => $user->activation->max_resend,
                            'user'              => $user
                        ];
        
                        return $this->successResponse('success resend', $resp);
                    }

                    return $this->errorResponse('max counter', Carbon::parse($user->activation->resend_avail_at)->isoFormat('D MMMM Y HH:m'));
                } else {
                    $user->activation->resend_avail_at = Carbon::now()->addDay();
                    $user->activation->save();

                    return $this->errorResponse('max counter', Carbon::parse($user->activation->resend_avail_at)->isoFormat('D MMMM Y HH:m'));
                }
            }
        } else {
            return $this->errorResponse('user not found', null);
        }
    }

    public function forgotPassword($request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $invalidated_request = ResetPassword::where('user_id', $user->id)->first();
            if ($invalidated_request) {
                $invalidated_request->delete();
            }

            $code = RandNumberGenerator(4);
            
            $input_reset_pass = [
                'user_id'   => $user->id,
                'code'      => $code
            ];

            ResetPassword::create($input_reset_pass);

            Http::post(env('MAIL_SERVICE_HOST') . '/v1/send/reset-password', [
                'sub'   => $user->name,
                'to'    => $user->email,
                'code'  => $code
            ]);

            return $this->successResponse('request forgot password success', null);
        } else {
            return $this->errorResponse('user not found', null);
        }
    }

    public function validateForgotPasswordCode($request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $invalidated_request = ResetPassword::where('user_id', $user->id)->first();
            if ($invalidated_request) {
                if (Carbon::now()->diffInMinutes(Carbon::parse($invalidated_request->created_at)) <= 10) {
                    if ($invalidated_request->total_fail <= $invalidated_request->max_fail) {
                        if ($invalidated_request->is_validated == true) {
                            return $this->successResponse('already validated', null);
                        }
                        
                        if ($invalidated_request->code == $request->code) {
                            $invalidated_request->is_validated = true;
                            $invalidated_request->save();
    
                            return $this->successResponse('validate reset password success', $user);
                        } else {
                            $invalidated_request->total_fail = $invalidated_request->total_fail + 1;
                            $invalidated_request->save();

                            return $this->errorResponse('invalid code', null);
                        }
                    } else {
                        $invalidated_request->delete();

                        return $this->errorResponse('maximum trial', null);
                    }
                } else {
                    $invalidated_request->delete();

                    return $this->errorResponse('expired code', null);
                }
            } else {
                return $this->errorResponse('there is no reset password request', null);
            }
        } else {
            return $this->errorResponse('user not found', null);
        }
    }

    public function resetPassword($request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $invalidated_request = ResetPassword::where('user_id', $user->id)->first();
            if ($invalidated_request) {
                if ($invalidated_request->is_validated == TRUE) {
                    $user->password = WpPassword::make($request->password);
                    $user->save();

                    $invalidated_request->delete();

                    return $this->successResponse('reset password success', null);
                } else {
                    return $this->errorResponse('need validation', null);
                }
            } else {
                return $this->errorResponse('there is no reset password request', null);
            }
        } else {
            return $this->errorResponse('user not found', null);
        }
    }
}
