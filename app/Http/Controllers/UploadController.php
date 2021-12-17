<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use phpseclib3\Crypt\Hash;

class UploadController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = Auth::id();
        $uploads = new Upload();
//        DB::enableQueryLog();
        $uploads = $uploads->where('user_id', $user);
        if($type = $request->get('type')){
            $uploads->where(function($query) use($type) {
                switch ($type){
                    case 'image':
                        $query = $query->where('mime','like','image')
                            ->orWhere('mime','like','image/gif')
                            ->orWhere('mime','like','image/ief')
                            ->orWhere('mime','like','image/jpeg')
                            ->orWhere('mime','like','image/jpg')
                            ->orWhere('mime','like','image/bmp')
                            ->orWhere('mime','like','image/tiff')
                            ->orWhere('mime','like','image/png')
                        ;
                        break;
                    case 'document':
                        $query = $query->where('mime','like','application/pdf')
                            ->orWhere('mime','like','application/msword')
                            ->orWhere('mime','like','powerpoint')
                            ->orWhere('mime','like','text/plain')
                            ->orWhere('mime','like','application/xml')
                            ->orWhere('mime','like','access');
                        break;
                    case 'video':
                        $query = $query->where('mime','like','video/mp4')
                            ->orWhere('mime','like','video/mpeg')
                            ->orWhere('mime','like','video/mp4')
                            ->orWhere('mime','like','video/ogg')
                            ->orWhere('mime','like','video/quicktime');
                        break;
                }
//            return $query;
            });
        }

        $uploads = $uploads->orderBy('id', 'desc')->get();
//        dd(DB::getQueryLog());

        $result = DocumentResource::collection($uploads);

        return $this->generateResponse($result, true, __('global.upload.list.success'), 200);
        //        return null;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'file.*' => 'mimes:jpg,png,gif,tiff,doc,docx,pdf,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->generateResponse(['error' => $validator->errors()], false, __('global.upload.error'), 401);
        }
        $request_files = $request->file('file');
        $files_array = $request_files;
        if (!is_array($request_files)) {
            $files_array = [];
            $files_array[0] = $request_files;
        }
        $upload_result = [];
        foreach ($files_array as $each_file) {
            $user = Auth::user();
            $user_id = Auth::id();
            $upload_path = env('UPLOAD_PATH','uploads') . '/' . $user->upload_path;

            $path = public_path($upload_path . '/original');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }
            $ext = $each_file->getClientOriginalExtension();
            $file_name = Str::random() . '.' . $ext;
            $size = $each_file->getSize();
            $type = $each_file->getClientMimeType();
            $imageTypes = ['image/jpeg', 'image/gif', 'image/png'];

            if (in_array($type, $imageTypes)) {
                $img = Image::make($each_file->path());
                $thumbnail_path = public_path($upload_path . '/thumbnail');
                $medium_path = public_path($upload_path . '/medium');
                $large_path = public_path($upload_path . '/large');
                if (!File::isDirectory($thumbnail_path)) {
                    File::makeDirectory($thumbnail_path);
                }
                if (!File::isDirectory($medium_path)) {
                    File::makeDirectory($medium_path);
                }
                if (!File::isDirectory($large_path)) {
                    File::makeDirectory($large_path);
                }
                $img->resize(env('LARGE_IMG_SIZE',1200), env('LARGE_IMG_SIZE',1200), function ($constraint) {
                    $constraint->aspectRatio();
                })->save($large_path . '/' . $file_name, 80);

                $img->resize(env('MEDIUM_IMG_SIZE',600), env('MEDIUM_IMG_SIZE',600), function ($constraint) {
                    $constraint->aspectRatio();
                })->save($medium_path . '/' . $file_name, 75);
                $img->resize(env('THUMBNAIL_IMG_SIZE',300), env('THUMBNAIL_IMG_SIZE',300), function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumbnail_path . '/' . $file_name, 70);

            }
            $file = $each_file->move($path, $file_name);
            //store your file into database
            $document = new Upload();
            $document->title = $file_name;
            $document->user_id = $user_id;
            $document->mime = $type;
            //$document->type    = $type;
            $document->size = $size;
            $document->path = $upload_path . '/original/' . $file_name;
            $document->save();
            $document->link = URL::to($document->path);

            $upload_result[] = $document;
        }
        return $this->generateResponse(DocumentResource::collection($upload_result), true, __('global.upload.success'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Upload $upload
     *
     * @return JsonResponse
     */
    public function show(Upload $upload)
    {
        $user = Auth::id();

        $result = Upload::findOrFail($upload->id);

        if ($result->user_id != $user) {
            return $this->generateResponse(null, false, __('global.unauthorized'), 403);
        }

        $result->link = URL::to($result->path);
        $data = new DocumentResource($result);

        return $this->generateResponse($data, true, __('global.upload.list.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Upload $upload
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Upload $upload)
    {
        //
    }
}
