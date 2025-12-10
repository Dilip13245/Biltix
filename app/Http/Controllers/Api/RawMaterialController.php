<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ImageHelper;

class RawMaterialController extends Controller
{
    use ApiResponseTrait;

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $imagePath = null;
        $originalImageName = null;
        $fileSize = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = ImageHelper::upload($image, 'raw_materials');
            $originalImageName = $image->getClientOriginalName();
            $fileSize = $image->getSize();
        }

        $rawMaterial = RawMaterial::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'image_path' => $imagePath,
            'original_image_name' => $originalImageName,
            'file_size' => $fileSize,
            'posted_by' => $request->user_id,
            'status' => 'pending',
        ]);

        return $this->successResponse(__('api.raw_materials.created_success'), $rawMaterial);
    }

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $rawMaterials = RawMaterial::where('project_id', $request->project_id)
            ->where('is_deleted', false)
            ->with(['postedBy:id,name', 'approvedBy:id,name', 'rejectedBy:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse(__('api.raw_materials.list_retrieved'), $rawMaterials);
    }

    public function details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raw_material_id' => 'required|exists:raw_materials,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $rawMaterial = RawMaterial::with(['postedBy:id,name', 'approvedBy:id,name', 'rejectedBy:id,name'])
            ->find($request->raw_material_id);

        if (!$rawMaterial || $rawMaterial->is_deleted) {
            return $this->errorResponse(__('api.raw_materials.not_found'), [], 404);
        }

        return $this->successResponse(__('api.raw_materials.details_retrieved'), $rawMaterial);
    }

    public function approve(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raw_material_id' => 'required|exists:raw_materials,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $rawMaterial = RawMaterial::find($request->raw_material_id);

        if (!$rawMaterial || $rawMaterial->is_deleted) {
            return $this->errorResponse(__('api.raw_materials.not_found'), [], 404);
        }

        $rawMaterial->update([
            'status' => 'approved',
            'approved_by' => $request->user_id,
            'approved_at' => now(),
        ]);

        return $this->successResponse(__('api.raw_materials.approved_success'), $rawMaterial);
    }

    public function reject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raw_material_id' => 'required|exists:raw_materials,id',
            'user_id' => 'required|exists:users,id',
            'rejection_note' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $rawMaterial = RawMaterial::find($request->raw_material_id);

        if (!$rawMaterial || $rawMaterial->is_deleted) {
            return $this->errorResponse(__('api.raw_materials.not_found'), [], 404);
        }

        $rawMaterial->update([
            'status' => 'rejected',
            'rejection_note' => $request->rejection_note,
            'rejected_by' => $request->user_id,
            'rejected_at' => now(),
        ]);

        return $this->successResponse(__('api.raw_materials.rejected_success'), $rawMaterial);
    }
}
