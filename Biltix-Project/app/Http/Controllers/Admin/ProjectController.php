<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['projectManager', 'teamMembers']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->latest()->paginate(10);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        return view('admin.projects.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_contact' => 'required|string|max:255',
            'project_manager_id' => 'required|exists:users,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['status'] = 'planning';
        $data['progress_percentage'] = 0;

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imageNames = ImageHelper::uploadMultiple(
                $request->file('images'),
                'projects',
                $request->name
            );
            $data['images'] = $imageNames;
        }

        Project::create($data);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show($id)
    {
        $project = Project::with([
            'projectManager',
            'teamMembers',
            'tasks',
            'inspections',
            'dailyLogs',
            'plans',
            'gallery'
        ])->findOrFail($id);

        return view('admin.projects.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $users = User::where('is_active', true)->get();
        return view('admin.projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_contact' => 'required|string|max:255',
            'project_manager_id' => 'required|exists:users,id',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $newImages = ImageHelper::uploadMultiple(
                $request->file('images'),
                'projects',
                $request->name
            );
            
            $existingImages = $project->images ?? [];
            $data['images'] = array_merge($existingImages, $newImages);
        }

        $project->update($data);

        return redirect()->route('admin.projects.show', $project->id)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        
        // Delete associated images
        if ($project->images) {
            ImageHelper::deleteMultiple('projects', $project->images);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}