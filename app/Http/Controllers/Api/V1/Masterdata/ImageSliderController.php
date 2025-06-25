<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\ImageSliderMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ImageSliderController extends Controller
{
    
    public function add(Request $request)
    {
        if (!isset($request->imageUrl)) {
            return response()->json([
                'status' => 'error',
                'message' => "Image File Is Required!",
            ], 422);
        }
        DB::beginTransaction();
        try {

            $imageSliderModel = new ImageSliderMedia();
            $imageSliderModel::query()->delete();
            foreach($request->imageUrl as $key => $val) {
                ImageSliderMedia::insert([
                    'type' => $request->typeFile[$key],
                    'value' => $val,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            DB::commit();
            Cache::flush();
            return response()->json([
                'status' => 200,
                'message' => 'Data updated!'
            ]);
        } catch (QueryException $e) {
            DB::rollBack();

            if ($e->errorInfo[1] == 1062) { 
                return response()->json([
                    'status' => 'error',
                    'message' => 'Duplicate entry for the same key. Please ensure the key is unique within the group.',
                ], 400);
            }

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit()
    {
        $data = ImageSliderMedia::where('deleted_at', null)->get();
        if ($data) {
            foreach($data as $image) {
                $name = explode('/', $image->value);
                $name[count($name) - 1] = 'thumbs/' . $name[count($name) - 1];
                $newUrl = implode('/', $name);

                
                $image->name = end($name);
                $image->thumb_url = $newUrl;
                $image->url = $image->value;
            }
        }
        return  response()->json([
            'data' => $data
        ]);
    }
}
