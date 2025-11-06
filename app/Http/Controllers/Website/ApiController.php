<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthApiService;
use App\Services\Api\ProjectApiService;
use App\Services\Api\TaskApiService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ApiController extends Controller
{
    use ApiResponseTrait;

    protected $authApi;
    protected $projectApi;
    protected $taskApi;

    public function __construct()
    {
        $this->authApi = new AuthApiService();
        $this->projectApi = new ProjectApiService();
        $this->taskApi = new TaskApiService();
    }

    // Auth Methods
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $response = $this->authApi->login([
            'email' => $request->email,
            'password' => $request->password,
            'device_type' => 'W', // Website
        ]);

        if ($response->isSuccess()) {
            Session::put('user', $response->getData());
            Session::put('user_id', $response->getData('id'));
        }

        return $this->handleApiResponse($response, route('dashboard'), route('login'));
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|min:6',
            'role' => 'required|in:contractor,consultant,site_engineer,project_manager,stakeholder',
            'company_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $response = $this->authApi->signup([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => $request->role,
            'company_name' => $request->company_name,
            'designation' => $request->designation,
            'employee_count' => $request->employee_count,
            'device_type' => 'W',
            'members' => $request->members ?? [],
        ]);

        if ($response->isSuccess()) {
            Session::put('user', $response->getData());
            Session::put('user_id', $response->getData('id'));
        }

        return $this->handleApiResponse($response, route('dashboard'), route('signup'));
    }

    public function logout(Request $request)
    {
        $userId = Session::get('user_id');
        
        if ($userId) {
            $this->authApi->logout(['user_id' => $userId]);
        }

        Session::flush();
        return redirect()->route('login')->with('success', __('Logged out successfully'));
    }

    public function getUserProfile()
    {
        $userId = Session::get('user_id');
        
        if (!$userId) {
            return $this->errorResponse('User not authenticated', [], 401);
        }

        $response = $this->authApi->getUserProfile(['user_id' => $userId]);
        return $this->handleApiResponse($response);
    }

    // Project Methods
    public function getProjects(Request $request)
    {
        $response = $this->projectApi->list([
            'user_id' => Session::get('user_id'),
            'page' => $request->get('page', 1),
            'limit' => $request->get('limit', 10),
            'search' => $request->get('search'), // Pass search parameter
            'type' => $request->get('status'), // Pass status/type parameter
        ]);

        return $this->handleApiResponse($response);
    }

    public function createProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $response = $this->projectApi->create([
            'user_id' => Session::get('user_id'),
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'project_type' => $request->project_type,
            'budget' => $request->budget,
        ]);

        return $this->handleApiResponse($response, route('projects.index'));
    }

    public function getProjectDetails($projectId)
    {
        $response = $this->projectApi->details([
            'user_id' => Session::get('user_id'),
            'project_id' => $projectId,
        ]);

        return $this->handleApiResponse($response);
    }

    // Task Methods
    public function getTasks(Request $request)
    {
        $response = $this->taskApi->list([
            'user_id' => Session::get('user_id'),
            'project_id' => $request->get('project_id'),
            'page' => $request->get('page', 1),
            'limit' => $request->get('limit', 10),
        ]);

        return $this->handleApiResponse($response);
    }

    public function createTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $response = $this->taskApi->create([
            'user_id' => Session::get('user_id'),
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority ?? 'medium',
            'assigned_to' => $request->assigned_to,
        ]);

        return $this->handleApiResponse($response, route('tasks.index'));
    }

    public function updateTaskStatus(Request $request, $taskId)
    {
        $response = $this->taskApi->changeStatus([
            'user_id' => Session::get('user_id'),
            'task_id' => $taskId,
            'status' => $request->status,
        ]);

        return $this->handleApiResponse($response);
    }
}