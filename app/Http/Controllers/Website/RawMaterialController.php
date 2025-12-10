<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RawMaterialController extends Controller
{
    public function index($project_id)
    {
        $project = Project::findOrFail($project_id);
        
        return view('website.raw-materials', compact('project'));
    }

    /**
     * Get all raw materials for a project
     */
    public function getMaterials($project_id)
    {
        try {
            $materials = RawMaterial::where('project_id', $project_id)
                ->active()
                ->with(['postedBy:id,name', 'approvedBy:id,name', 'rejectedBy:id,name'])
                ->orderBy('created_at', 'desc')
                ->get();

            $materials = $materials->map(function ($material) {
                return [
                    'id' => $material->id,
                    'name' => $material->name,
                    'quantity' => $material->quantity,
                    'description' => $material->description,
                    'image_url' => $material->image_path ? asset('storage/' . $material->image_path) : null,
                    'status' => $material->status,
                    'rejection_note' => $material->rejection_note,
                    'posted_by' => $material->postedBy ? $material->postedBy->name : 'Unknown',
                    'approved_by' => $material->approvedBy ? $material->approvedBy->name : null,
                    'rejected_by' => $material->rejectedBy ? $material->rejectedBy->name : null,
                    'created_at' => $material->created_at->format('d/m/Y'),
                    'approved_at' => $material->approved_at ? $material->approved_at->format('d/m/Y H:i') : null,
                    'rejected_at' => $material->rejected_at ? $material->rejected_at->format('d/m/Y H:i') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $materials
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch materials',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new raw material
     */
    public function store(Request $request, $project_id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240', // 10MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if project exists
            $project = Project::findOrFail($project_id);

            $data = [
                'project_id' => $project_id,
                'name' => $request->name,
                'quantity' => $request->quantity,
                'description' => $request->description,
                'posted_by' => auth()->id(),
                'status' => 'pending'
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $originalName = $image->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                $path = $image->storeAs('raw_materials', $fileName, 'public');

                $data['image_path'] = $path;
                $data['original_image_name'] = $originalName;
                $data['file_size'] = $image->getSize();
            }

            $material = RawMaterial::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Material added successfully',
                'data' => $material
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a raw material
     */
    public function update(Request $request, $project_id, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'quantity' => 'sometimes|required|integer|min:1',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $material = RawMaterial::where('project_id', $project_id)
                ->where('id', $id)
                ->active()
                ->firstOrFail();

            $data = $request->only(['name', 'quantity', 'description']);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($material->image_path && Storage::disk('public')->exists($material->image_path)) {
                    Storage::disk('public')->delete($material->image_path);
                }

                $image = $request->file('image');
                $originalName = $image->getClientOriginalName();
                $fileName = time() . '_' . $originalName;
                $path = $image->storeAs('raw_materials', $fileName, 'public');

                $data['image_path'] = $path;
                $data['original_image_name'] = $originalName;
                $data['file_size'] = $image->getSize();
            }

            $material->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Material updated successfully',
                'data' => $material
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a raw material
     */
    public function approve(Request $request, $project_id, $id)
    {
        try {
            $material = RawMaterial::where('project_id', $project_id)
                ->where('id', $id)
                ->active()
                ->firstOrFail();

            $material->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_note' => null,
                'rejected_by' => null,
                'rejected_at' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Material approved successfully',
                'data' => $material
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a raw material
     */
    public function reject(Request $request, $project_id, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rejection_note' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $material = RawMaterial::where('project_id', $project_id)
                ->where('id', $id)
                ->active()
                ->firstOrFail();

            $material->update([
                'status' => 'rejected',
                'rejection_note' => $request->rejection_note,
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'approved_by' => null,
                'approved_at' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Material rejected successfully',
                'data' => $material
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject material',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a raw material (soft delete)
     */
    public function destroy($project_id, $id)
    {
        try {
            $material = RawMaterial::where('project_id', $project_id)
                ->where('id', $id)
                ->active()
                ->firstOrFail();

            $material->update([
                'is_deleted' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Material deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete material',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
