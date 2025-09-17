<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'photos' => 'required|array',
                'photos.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $uploadedPhotos = [];

            foreach ($request->file('photos') as $photo) {
                $fileData = FileHelper::uploadFile($photo, 'photos');
                
                $photoDetails = new Photo();
                $photoDetails->project_id = $request->project_id;
                $photoDetails->phase_id = $request->phase_id;
                $photoDetails->title = $request->title ?? '';
                $photoDetails->description = $request->description ?? '';
                $photoDetails->file_name = $fileData['filename'];
                $photoDetails->file_path = $fileData['path'];
                $photoDetails->file_size = $fileData['size'];
                $photoDetails->taken_at = $request->taken_at ?? now();
                $photoDetails->taken_by = $request->user_id;
                $photoDetails->location = $request->location ?? '';
                $photoDetails->tags = $request->tags ?? [];
                $photoDetails->is_active = true;
                $photoDetails->save();

                // Add full URL to the uploaded photo
                $photoDetails->file_url = asset('storage/' . $photoDetails->file_path);
                if ($photoDetails->thumbnail_path) {
                    $photoDetails->thumbnail_url = asset('storage/' . $photoDetails->thumbnail_path);
                }
                
                $uploadedPhotos[] = $photoDetails;
            }

            return $this->toJsonEnc($uploadedPhotos, trans('api.photos.uploaded_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function list(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $project_id = $request->input('project_id');
            $phase_id = $request->input('phase_id');
            $limit = $request->input('limit', 20);

            $query = Photo::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($phase_id) {
                $query->where('phase_id', $phase_id);
            }

            $photos = $query->with(['uploader:id,name'])->orderBy('taken_at', 'desc')->paginate($limit);
            
            // Add full URLs to file paths
            $photos->getCollection()->transform(function ($photo) {
                $photo->file_url = asset('storage/' . $photo->file_path);
                if ($photo->thumbnail_path) {
                    $photo->thumbnail_url = asset('storage/' . $photo->thumbnail_path);
                }
                return $photo;
            });

            return $this->toJsonEnc($photos, trans('api.photos.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function gallery(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $project_id = $request->input('project_id');
            $date = $request->input('date');

            $query = Photo::where('is_active', 1)->where('is_deleted', 0);

            if ($project_id) {
                $query->where('project_id', $project_id);
            }

            if ($date) {
                $query->whereDate('taken_at', $date);
            }

            $photos = $query->orderBy('taken_at', 'desc')->get();
            
            // Add full URLs to file paths
            $photos->transform(function ($photo) {
                $photo->file_url = asset('storage/' . $photo->file_path);
                if ($photo->thumbnail_path) {
                    $photo->thumbnail_url = asset('storage/' . $photo->thumbnail_path);
                }
                return $photo;
            });

            // Group by date
            $groupedPhotos = $photos->groupBy(function($photo) {
                return $photo->taken_at->format('Y-m-d');
            });

            return $this->toJsonEnc($groupedPhotos, trans('api.photos.gallery_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function addTags(Request $request)
    {
        try {
            $photo_id = $request->input('photo_id');
            $tags = $request->input('tags', []);
            $user_id = $request->input('user_id');

            $photo = Photo::where('id', $photo_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$photo) {
                return $this->toJsonEnc([], trans('api.photos.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $photo->tags = $tags;
            $photo->save();

            return $this->toJsonEnc($photo, trans('api.photos.tags_added'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $photo_id = $request->input('photo_id');
            $user_id = $request->input('user_id');

            $photo = Photo::where('id', $photo_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$photo) {
                return $this->toJsonEnc([], trans('api.photos.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $photo->is_deleted = true;
            $photo->save();

            return $this->toJsonEnc([], trans('api.photos.deleted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}