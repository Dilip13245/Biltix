<?php

namespace App\Http\Controllers\Api;

use App\Helpers\EncryptDecrypt;
use App\Helpers\FileHelper;
use App\Helpers\TwilioHelper;
use App\Http\Controllers\Controller;
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

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try {
            // Check for recent signup attempts from same IP/email
            $recentAttempt = User::where('email', $request->email)
                ->where('created_at', '>', now()->subMinutes(1))
                ->first();
                
            if ($recentAttempt) {
                return $this->toJsonEnc([], 'Account already exists or created recently', Config::get('constant.ERROR'));
            }
            
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'name' => 'required|string|max:255',
                'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'role' => 'required|in:contractor,consultant,site_engineer,project_manager,stakeholder',
                'company_name' => 'required|string|max:255',
                'designation' => 'nullable|string|max:255',
                'employee_count' => 'nullable|integer',
                'members' => 'nullable|array',
                'members.*.member_name' => 'required_with:members|string|max:255',
                'members.*.member_phone' => 'required_with:members|string|max:20',
                'device_type' => 'required|in:A,I,W',
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
            $userDetails->is_active = true;
            $userDetails->is_verified = true;

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

                // Generate token and create device entry (auto-login)
                $accessToken = bin2hex(random_bytes(32)); // Cryptographically secure

                $deviceData = [
                    'user_id' => $userDetails->id,
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
                    ['user_id' => $userDetails->id],
                    $deviceData
                );

                // Get user members
                $members = UserMember::where('user_id', $userDetails->id)
                    ->where('is_active', 1)
                    ->select('member_name', 'member_phone')
                    ->get();

                $userDetails->token = $accessToken;
                $userDetails->device_type = $request->device_type;
                $userDetails->members = $members;

                return $this->toJsonEnc($userDetails, trans('api.auth.signup_success'), Config::get('constant.SUCCESS'));
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
                'device_type' => 'required|in:A,I,W',
            ]);

            $validator->setCustomMessages([
                'email.required' => trans('api.auth.email_required'),
                'password.required' => trans('api.auth.password_required'),
                'device_type.required' => trans('api.auth.device_type_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // First, find user by email only
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is deleted
            if ($user->is_deleted == 1) {
                return $this->toJsonEnc([], trans('api.auth.account_deleted_error'), Config::get('constant.ERROR'));
            }

            // Check if user is not verified
            if ($user->is_verified != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_not_verified'), Config::get('constant.ERROR'));
            }

            // Check if user is inactive
            if ($user->is_active != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_inactive'), Config::get('constant.ERROR'));
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->toJsonEnc([], trans('api.auth.invalid_password'), Config::get('constant.ERROR'));
            }

            $accessToken = bin2hex(random_bytes(32)); // Cryptographically secure

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
                'type' => 'required|in:forgot',
                'phone' => 'required|string',
            ], [
                'type.required' => trans('api.auth.type_required'),
                'type.in' => trans('api.auth.type_invalid'),
                'phone.required' => trans('api.auth.phone_number_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $phone = $request->input('phone');
            $otp = '123456';

            // Find user by phone only
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found_phone'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is deleted
            if ($user->is_deleted == 1) {
                return $this->toJsonEnc([], trans('api.auth.account_deleted_error'), Config::get('constant.ERROR'));
            }

            // Check if user is not verified
            if ($user->is_verified != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_not_verified'), Config::get('constant.ERROR'));
            }

            // Check if user is inactive
            if ($user->is_active != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_inactive'), Config::get('constant.ERROR'));
            }

            try {
                Log::info('OTP for phone ' . $phone . ': ' . $otp);

                $user->otp = $otp;
                $user->otp_expires_at = now()->addMinutes(10);
                $user->save();
            } catch (\Exception $e) {
                Log::error('OTP process failed: ' . $e->getMessage());
                return $this->toJsonEnc([], 'Failed to process OTP', Config::get('constant.ERROR'));
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
                'phone' => 'required|string',
                'otp' => 'required|string',
                'type' => 'required|in:forgot',
            ], [
                'phone.required' => trans('api.auth.phone_number_required'),
                'otp.required' => trans('api.auth.otp_required'),
                'type.required' => trans('api.auth.type_required'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $phone = $request->input('phone');
            $otp = $request->input('otp');
            $type = $request->input('type');

            // Find user by phone only
            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found_phone'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is deleted
            if ($user->is_deleted == 1) {
                return $this->toJsonEnc([], trans('api.auth.account_deleted_error'), Config::get('constant.ERROR'));
            }

            // Check if user is not verified
            if ($user->is_verified != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_not_verified'), Config::get('constant.ERROR'));
            }

            // Check if user is inactive
            if ($user->is_active != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_inactive'), Config::get('constant.ERROR'));
            }

            if ($user->otp != $otp) {
                return $this->toJsonEnc([], trans('api.auth.invalid_otp'), Config::get('constant.ERROR'));
            }

            if ($user->otp_expires_at && now()->gt($user->otp_expires_at)) {
                return $this->toJsonEnc([], trans('api.auth.otp_expired'), Config::get('constant.ERROR'));
            }

            $user->otp = null;
            $user->otp_expires_at = null;


            $user->save();

            $message = $type === 'signup' ? trans('api.auth.account_verified') : trans('api.auth.otp_verified');
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
                'phone' => 'required|string',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password',
            ], [
                'phone.required' => trans('api.auth.phone_number_required'),
                'new_password.required' => trans('api.auth.new_password_required'),
                'new_password.min' => trans('api.auth.new_password_min'),
                'confirm_password.required' => trans('api.auth.confirm_password_required'),
                'confirm_password.same' => trans('api.auth.confirm_password_mismatch'),
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Find user by phone only
            $user = User::where('phone', $request->phone)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found_phone'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is deleted
            if ($user->is_deleted == 1) {
                return $this->toJsonEnc([], trans('api.auth.account_deleted_error'), Config::get('constant.ERROR'));
            }

            // Check if user is not verified
            if ($user->is_verified != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_not_verified'), Config::get('constant.ERROR'));
            }

            // Check if user is inactive
            if ($user->is_active != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_inactive'), Config::get('constant.ERROR'));
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
            // Find user by ID only
            $user = User::where('id', $user_id)->first();

            if (!$user) {
                return $this->toJsonEnc([], trans('api.auth.user_not_found_id'), Config::get('constant.NOT_FOUND'));
            }

            // Check if user is deleted
            if ($user->is_deleted == 1) {
                return $this->toJsonEnc([], trans('api.auth.account_deleted_error'), Config::get('constant.ERROR'));
            }

            // Check if user is not verified
            if ($user->is_verified != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_not_verified'), Config::get('constant.ERROR'));
            }

            // Check if user is inactive
            if ($user->is_active != 1) {
                return $this->toJsonEnc([], trans('api.auth.account_inactive'), Config::get('constant.ERROR'));
            }

            // Get user members
            $members = UserMember::where('user_id', $user->id)
                ->where('is_active', 1)
                ->select('member_name', 'member_phone')
                ->get();

            // Get project IDs created by user
            $createdProjectIds = \App\Models\Project::where('created_by', $user->id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->pluck('id');

            // Get project IDs assigned to user via team_members
            $assignedProjectIds = \App\Models\TeamMember::where('user_id', $user->id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->pluck('project_id');

            // Merge both project IDs
            $allProjectIds = $createdProjectIds->merge($assignedProjectIds)->unique();

            // Count total projects
            $totalProjects = $allProjectIds->count();

            // Count tasks from these projects
            $totalTasks = \App\Models\Task::whereIn('project_id', $allProjectIds)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            // Count pending tasks from these projects
            $totalPendingTasks = \App\Models\Task::whereIn('project_id', $allProjectIds)
                ->whereIn('status', ['in_progress', 'todo'])
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

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
                'total_employees' => $user->employee_count ?? 0,
                'total_projects' => $totalProjects,
                'total_tasks' => $totalTasks,
                'total_pending_tasks' => $totalPendingTasks
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

    public function validateSignupStep(Request $request)
    {
        try {
            $step = $request->input('step');
            
            if ($step == 1) {
                // Step 1: Basic user details
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|unique:users,phone',
                    'name' => 'required|string|max:255',
                    'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ], [
                    'email.required' => trans('api.auth.email_required'),
                    'email.email' => trans('api.auth.email_invalid'),
                    'email.unique' => trans('api.auth.email_unique'),
                    'phone.required' => trans('api.auth.phone_number_required'),
                    'phone.unique' => trans('api.auth.phone_number_unique'),
                    'name.required' => trans('api.auth.name_required'),
                    'password.required' => trans('api.auth.password_required'),
                    'password.min' => trans('api.auth.password_min'),
                ]);
            } elseif ($step == 2) {
                // Step 2: Company details + Step 1 validation
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|unique:users,email',
                    'phone' => 'required|unique:users,phone',
                    'name' => 'required|string|max:255',
                    'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                    'company_name' => 'required|string|max:255',
                    'designation' => 'required|in:contractor,consultant,site_engineer,project_manager,stakeholder',
                    'employee_count' => 'required|integer|min:1',
                ], [
                    'email.required' => trans('api.auth.email_required'),
                    'email.email' => trans('api.auth.email_invalid'),
                    'email.unique' => trans('api.auth.email_unique'),
                    'phone.required' => trans('api.auth.phone_number_required'),
                    'phone.unique' => trans('api.auth.phone_number_unique'),
                    'name.required' => trans('api.auth.name_required'),
                    'password.required' => trans('api.auth.password_required'),
                    'password.min' => trans('api.auth.password_min'),
                    'company_name.required' => trans('api.auth.company_name_required'),
                    'designation.required' => trans('api.auth.role_required'),
                    'employee_count.required' => trans('api.auth.employee_count_required'),
                ]);
            } else {
                return $this->toJsonEnc([], 'Invalid step', Config::get('constant.ERROR'));
            }
            
            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }
            
            return $this->toJsonEnc([], 'Step validation successful', Config::get('constant.SUCCESS'));
            
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}
