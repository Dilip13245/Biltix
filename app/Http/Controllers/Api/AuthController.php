<?php

namespace App\Http\Controllers\Api;

use App\Helpers\EncryptDecrypt;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\UserMember;
use Illuminate\Support\Facades\Mail;

class AuthController extends BaseController
{
    /**
     * Send response to user
     */
    public function toJsonEnc($result = [], $message = '', $status = 200)
    {
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                'code' => $status,
                'message' => $message,
                'data' => ! empty($result) ? $result : new \stdClass(),
            ])), $status);
        } else {
            return response()->json([
                'code' => $status,
                'message' => $message,
                'data' => !empty($result) ? $result : new \stdClass(),
            ], $status);
        }
    }

    public function validateResponse($errors, $result = [])
    {
        $err = '';
        foreach ($errors->all() as $key => $val) {
            $err = $val;
            break;
        }
        if (Config::get('constant.ENCRYPTION_ENABLED') == 1) {
            return response()->json(EncryptDecrypt::bodyEncrypt(json_encode([
                'code' => Config::get('constant.ERROR'),
                'message' => $err,
                'data' => ! empty($result) ? $result : new \stdClass(),
            ])));
        } else {
            return response()->json([
                'code' => Config::get('constant.ERROR'),
                'message' => $err,
                'data' => ! empty($result) ? $result : new \stdClass(),
            ]);
        }
    }

    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'name' => 'required|string|max:255',
                'password' => 'required|min:6',
                'role' => 'required|in:contractor,consultant,site_engineer,project_manager,stakeholder',
                'company_name' => 'required|string|max:255',
                'designation' => 'nullable|string|max:255',
                'employee_count' => 'nullable|integer',
                'members' => 'nullable|array',
                'members.*.member_name' => 'required_with:members|string|max:255',
                'members.*.member_phone' => 'required_with:members|string|max:20',
                'device_type' => 'required|in:A,I',
            ], [
                'email.required' => trans('api.auth.email_required'),
                'email.email' => trans('api.auth.email_invalid'),
                'email.unique' => trans('api.auth.email_unique'),
                'phone.required' => trans('api.auth.phone_number_required'),
                'phone.unique' => trans('api.auth.phone_number_unique'),
                'name.required' => trans('api.auth.name_required'),
                'password.required' => trans('api.auth.password_required'),
                'password.min' => trans('api.auth.password_min'),
                'role.required' => trans('api.auth.role_required'),
                'company_name.required' => trans('api.auth.company_name_required'),
                'device_type.required' => trans('api.auth.device_type_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $userDetails = new User();
            $userDetails->email = $request->email;
            $userDetails->phone = $request->phone;
            $userDetails->name = $request->name;
            $userDetails->password = Hash::make($request->password);
            $userDetails->role = $request->role;
            $userDetails->company_name = $request->company_name;
            $userDetails->designation = $request->designation ?? '';
            $userDetails->employee_count = $request->employee_count ?? null;
            $userDetails->is_active = false;

            if ($userDetails->save()) {
                // Save multiple members if provided
                if ($request->has('members') && is_array($request->members)) {
                    foreach ($request->members as $member) {
                        UserMember::create([
                            'user_id' => $userDetails->id,
                            'member_name' => $member['member_name'],
                            'member_phone' => $member['member_phone']
                        ]);
                    }
                }

                return $this->toJsonEnc(['user_id' => $userDetails->id], 'User registered successfully. Please verify OTP to activate account.', Config::get('constant.SUCCESS'));
            } else {
                return $this->toJsonEnc([], trans('api.auth.signup_error'), Config::get('constant.ERROR'));
            }
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
                'device_type' => 'required|in:A,I',
            ]);

            $validator->setCustomMessages([
                'email.required' => trans('api.auth.email_required'),
                'password.required' => trans('api.auth.password_required'),
                'device_type.required' => trans('api.auth.device_type_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $user = User::where('email', $request->email)
                ->where('is_active', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_available'), Config::get('constant.NOT_FOUND'));
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->toJsonEnc([], trans('api.auth.invalid_password'), Config::get('constant.ERROR'));
            }

            $accessToken = Str::random(64);

            $deviceData = [
                'user_id' => $user->id,
                'token' => $accessToken,
                'device_type' => $request->device_type,
                'ip_address' => $request->ip_address ?? "",
                'uuid' => $request->uuid ?? "",
                'os_version' => $request->os_version ?? "",
                'device_model' => $request->device_model ?? "",
                'app_version' => $request->app_version ?? 'v1',
                'device_token' => $request->device_token ?? "",
            ];

            UserDevice::updateOrCreate(
                ['user_id' => $user->id],
                $deviceData
            );

            // Update last login time
            $user->last_login_at = now();
            $user->save();

            // Get user members
            $members = UserMember::where('user_id', $user->id)
                ->where('is_active', 1)
                ->select('member_name', 'member_phone')
                ->get();

            $user->token = $accessToken;
            $user->device_type = $request->device_type;
            $user->profile_image = $user->profile_image
                ? asset('storage/profile/' . $user->profile_image)
                : null;
            $user->members = $members;

            return $this->toJsonEnc($user, trans('api.auth.login_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|in:forgot,signup',
                'email' => 'required|email',
            ], [
                'type.required' => trans('api.auth.type_required'),
                'type.in' => trans('api.auth.type_invalid'),
                'email.required' => trans('api.auth.email_required'),
                'email.email' => trans('api.auth.email_invalid'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $email = $request->input('email');
            $type = $request->input('type');
            // $otp = rand(100000, 999999);
            $otp = '123456';

            $user = User::where('email', $email)->first();

            // Type: forgot → user must exist and be active
            if ($type === 'forgot') {
                if (!$user || $user->is_active != 1) {
                    return $this->toJsonEnc([], trans('api.auth.user_not_found_or_inactive'), Config::get('constant.ERROR'));
                }
            }

            // Type: signup → user must NOT exist
            if ($type === 'signup') {
                if ($user) {
                    if ($user->is_active == 1) {
                        return $this->toJsonEnc([], trans('api.auth.user_already_exists'), Config::get('constant.ERROR'));
                    }
                }
            }

            try {
                $data = ['otp' => $otp];

                // Mail::send('emails.otp', $data, function ($message) use ($email) {
                //     $message->to($email)
                //         ->subject('Your OTP Code - Biltix');
                // });

                if ($user) {
                    $user->otp = $otp;
                    $user->otp_expires_at = now()->addMinutes(10);
                    $user->save();
                }
            } catch (\Exception $e) {
                Log::error('OTP email failed: ' . $e->getMessage());
                return $this->toJsonEnc([], trans('api.auth.otp_email_failed'), Config::get('constant.ERROR'));
            }

            return $this->toJsonEnc(['otp' => $otp], trans('api.auth.otp_sent'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            Log::error('sendOtp exception: ' . $e->getMessage());
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp' => 'required|string',
                'type' => 'required|in:signup,forgot',
            ], [
                'email.required' => trans('api.auth.email_required'),
                'email.email' => trans('api.auth.email_invalid'),
                'otp.required' => trans('api.auth.otp_required'),
                'type.required' => trans('api.auth.type_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $email = $request->input('email');
            $otp = $request->input('otp');
            $type = $request->input('type');

            $user = User::where('email', $email)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found_or_inactive'), Config::get('constant.ERROR'));
            }

            if ($user->otp != $otp) {
                return $this->toJsonEnc([], trans('api.auth.invalid_otp'), Config::get('constant.ERROR'));
            }

            if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
                return $this->toJsonEnc([], 'OTP has expired', Config::get('constant.ERROR'));
            }

            $user->otp = null;
            $user->otp_expires_at = null;

            // Only for signup: set verified and active
            if ($type === 'signup') {
                $user->is_verified = true;
                $user->is_active = true;
            }

            $user->save();

            $message = $type === 'signup' ? 'Account verified and activated successfully' : 'OTP verified successfully';
            return $this->toJsonEnc(['user_id' => $user->id], $message, Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            Log::error('verifyOtp error: ' . $e->getMessage());
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ], [
                'email.required' => trans('api.auth.email_required'),
                'new_password.required' => trans('api.auth.new_password_required'),
                'new_password.min' => trans('api.auth.new_password_min'),
                'confirm_password.required' => trans('api.auth.confirm_password_required'),
                'confirm_password.same' => trans('api.auth.confirm_password_mismatch'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $user = User::where('email', $request->email)
                ->where('is_active', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found_or_inactive'), Config::get('constant.ERROR'));
            }

            if (Hash::check($request->new_password, $user->password)) {
                return $this->toJsonEnc([], trans('api.auth.password_same_as_old'), Config::get('constant.ERROR'));
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->toJsonEnc([], trans('api.auth.password_reset_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getUserProfile(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = User::where('id', $user_id)
                ->where('is_active', 1)
                ->where('is_verified', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found'), Config::get('constant.ERROR'));
            }

            // Get user members
            $members = UserMember::where('user_id', $user->id)
                ->where('is_active', 1)
                ->select('member_name', 'member_phone')
                ->get();

            // Prepare profile response data
            $profileData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'company_name' => $user->company_name,
                'designation' => $user->designation,
                'employee_count' => $user->employee_count,
                'profile_image' => $user->profile_image ? asset('storage/profile/' . $user->profile_image) : null,
                'is_verified' => $user->is_verified,
                'last_login_at' => $user->last_login_at,
                'created_at' => $user->created_at,
                'members' => $members,
                'total_members' => $members->count(),
                'total_employees' => $user->employee_count ?? 0
            ];

            return $this->toJsonEnc($profileData, trans('api.auth.user_profile_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'name' => 'nullable|string|max:255',
                'email' => [
                    'nullable',
                    'email',
                    Rule::unique('users')->ignore($request->user_id),
                ],
                'phone' => [
                    'nullable',
                    Rule::unique('users', 'phone')->ignore($request->user_id),
                ],
                'company_name' => 'nullable|string|max:255',
                'designation' => 'nullable|string|max:255',
                'password' => 'nullable|min:6',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $user = User::find($request->user_id);

            if (!$user || !$user->is_active || !$user->is_verified || $user->is_deleted) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found_or_inactive'), Config::get('constant.ERROR'));
            }

            // Update user details
            if ($request->filled('name')) $user->name = $request->name;
            if ($request->filled('email')) $user->email = $request->email;
            if ($request->filled('phone')) $user->phone = $request->phone;
            if ($request->filled('company_name')) $user->company_name = $request->company_name;
            if ($request->filled('designation')) $user->designation = $request->designation;

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($user->profile_image) {
                    FileHelper::deleteFile('profile/' . $user->profile_image);
                }
                $filename = FileHelper::uploadImage($request->file('profile_image'), 'profile');
                $user->profile_image = $filename;
            }

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            // Prepare response data
            $responseData = $user->toArray();
            $responseData['profile_image'] = $user->profile_image
                ? asset('storage/profile/' . $user->profile_image)
                : null;

            return $this->toJsonEnc($responseData, trans('api.auth.profile_updated_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function logout(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = User::where('id', $user_id)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.invalid_user'), Config::get('constant.ERROR'));
            }

            UserDevice::where('user_id', $user->id)->update([
                'device_token' => "",
                'token' => "",
            ]);

            return $this->toJsonEnc([], trans('api.auth.logout_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $user = User::where('id', $user_id)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.invalid_user'), Config::get('constant.ERROR'));
            }

            $user->is_active = false;
            $user->is_deleted = true;
            $user->deleted_at = now();
            $user->save();

            UserDevice::where('user_id', $user->id)->update([
                'device_token' => '',
                'token' => '',
            ]);

            return $this->toJsonEnc([], trans('api.auth.account_deleted'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}
