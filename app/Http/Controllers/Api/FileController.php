<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\FileCategory;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'category_id' => 'required|integer',
                'files' => 'required|array',
                'files.*' => 'file|max:10240', // 10MB max
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $uploadedFiles = [];

            foreach ($request->file('files') as $file) {
                $fileData = FileHelper::uploadFile($file, 'project_files');
                
                $fileRecord = File::create([
                    'project_id' => $request->project_id,
                    'category_id' => $request->category_id,
                    'name' => $fileData['filename'],
                    'original_name' => $fileData['original_name'],
                    'file_path' => $fileData['path'],
                    'file_size' => $fileData['size'],
                    'file_type' => $fileData['mime_type'],
                    'uploaded_by' => $request->user_id,
                    'is_public' => $request->is_public ?? false,
                ]);

                $uploadedFiles[] = $fileRecord;
            }

            return $this->toJsonEnc($uploadedFiles, trans('api.files.uploaded_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function list(Request $request)
    {
        try {
            $query = File::active();

            if ($request->project_id) {
                $query->where('project_id', $request->project_id);
            }

            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            $files = $query->paginate($request->limit ?? 10);
            
            // Add full URLs to file paths
            $files->getCollection()->transform(function ($file) {
                $file->file_url = asset('storage/' . $file->file_path);
                return $file;
            });

            return $this->toJsonEnc($files, trans('api.files.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $file = File::active()->find($request->file_id);

            if (!$file) {
                return $this->toJsonEnc([], trans('api.files.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Delete physical file
            FileHelper::deleteFile($file->file_path);

            // Soft delete record
            $file->update(['is_deleted' => true]);

            return $this->toJsonEnc([], trans('api.files.deleted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function categories(Request $request)
    {
        try {
            $categories = FileCategory::active()->get();

            return $this->toJsonEnc($categories, trans('api.files.categories_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function download(Request $request)
    {
        try {
            $file_id = $request->input('file_id');
            $user_id = $request->input('user_id');

            $file = File::active()->find($file_id);

            if (!$file) {
                return $this->toJsonEnc([], trans('api.files.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $downloadData = [
                'file_id' => $file->id,
                'file_name' => $file->original_name,
                'file_path' => asset('storage/' . $file->file_path),
                'file_size' => $file->file_size,
                'file_type' => $file->file_type,
            ];

            return $this->toJsonEnc($downloadData, trans('api.files.download_ready'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function share(Request $request)
    {
        try {
            $file_id = $request->input('file_id');
            $user_id = $request->input('user_id');
            $share_with = $request->input('share_with', []); // Array of user IDs

            $file = File::active()->find($file_id);

            if (!$file) {
                return $this->toJsonEnc([], trans('api.files.not_found'), Config::get('constant.NOT_FOUND'));
            }

            // Here you would typically create file_shares table entries
            $shareData = [
                'file_id' => $file->id,
                'shared_by' => $user_id,
                'shared_with' => $share_with,
                'share_link' => asset('storage/' . $file->file_path),
                'shared_at' => now(),
            ];

            return $this->toJsonEnc($shareData, trans('api.files.shared_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function search(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $project_id = $request->input('project_id');
            $search_term = $request->input('search_term');
            $file_type = $request->input('file_type');
            $limit = $request->input('limit', 10);

            $query = File::active();

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($search_term) {
                $query->where(function($q) use ($search_term) {
                    $q->where('name', 'like', "%{$search_term}%")
                      ->orWhere('original_name', 'like', "%{$search_term}%");
                });
            }

            if ($file_type) {
                $query->where('file_type', 'like', "%{$file_type}%");
            }

            $files = $query->paginate($limit);
            
            // Add full URLs to file paths
            $files->getCollection()->transform(function ($file) {
                $file->file_url = asset('storage/' . $file->file_path);
                return $file;
            });

            return $this->toJsonEnc($files, trans('api.files.search_results'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}