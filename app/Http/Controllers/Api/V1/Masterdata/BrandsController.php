<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Cache;

class BrandsController extends Controller
{

    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'order_number');
        $sortAsc   = $request->input('sort_asc', false);

        $data = new Brands();
        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }
        if($search){
            $data = $data->where(function($query) use($search){
                $query->where('name','like','%'.$search.'%');
            });
        }
        $data = $data->orderBy($sortField ? $sortField : 'order_number', $sortAsc ? 'desc' : 'asc');
        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }

    public function select(Request $request)
    {
        $data = Brands::get();

        $datas = [];
        foreach($data as $key){
            $datas[] = [
                'id' => $key->id,
                'text' => $key->name
            ];
        }
        return json_encode($datas);
    }

    public function add(Request $request)
    {
        $validator = validator($request->post(),[
            'order_number' => ['required','unique:brands,order_number']
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->get('order_number');
            return response([
                'message' => $message[0]
            ],400);
        }

        $data = new Brands;
        $data->name = $request->name;
        $data->slug = $this->slugify($request->name);
        $data->description_id = $request->description_id;
        $data->description_en = $request->description_en;
        $data->logo = $request->previewImages;
        $data->banner = $request->previewImagesBanner;


        $data->keyword_id = $request->keyword_id;
        $data->keyword_en = $request->keyword_en;
        $data->meta_title_id = $request->meta_title_id;
        $data->meta_title_en = $request->meta_title_en;
        $data->meta_description_id = $request->meta_description_id;
        $data->meta_description_en = $request->meta_description_en;
        $data->order_number = $request->order_number;
        $data->save();

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create brand {$request->name}";
        $user_log->save();
        Cache::flush();
        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function edit($id)
    {
        $data = Brands::where('id', $id)->first();

        return  response()->json([
            'data' => $data
        ]);
    }

    public function delete($id,Request $request)
    {
        $data = Brands::where('id', $id);

        if(!$data->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Brands::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete brands {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $data = Brands::where('id', $id)->withTrashed();

        if(!$data->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Brands::where('id', $id)->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} restore brands {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function update(Request $request)
    {
        if (!isset($request->order_number)) {
            return response(['message' => 'order number is required'],400);
        }

        $data = Brands::findOrFail($request->id);

       if ($data->order_number != $request->order_number) {
            $validator = validator($request->post(),[
                'order_number' => ['unique:brands,order_number']
            ]);

            if ($validator->fails()) {
                $message = $validator->errors()->get('order_number');
                return response([
                    'message' => $message[0]
                ],400);
            }
       }

        $data->name = $request->name;
        $data->slug = $this->slugify($request->name);
        $data->description_id = $request->description_id;
        $data->description_en = $request->description_en;
        $data->logo = $request->previewImages;
        $data->banner = $request->previewImagesBanner;

        $data->keyword_id = $request->keyword_id;
        $data->keyword_en = $request->keyword_en;
        $data->meta_title_id = $request->meta_title_id;
        $data->meta_title_en = $request->meta_title_en;
        $data->meta_description_id = $request->meta_description_id;
        $data->meta_description_en = $request->meta_description_en;

        $data->order_number = $request->order_number;

        if(!$data->update()){
            $data = [
                'status' => 0,
            ];
            return $data;
        }else{
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} update brand {$request->name}";
            $user_log->save();

            $data = [
                'status' => 1,
                'data' => $data
            ];
            return $data;
        }
    }

    private function slugify($string) {
        // Convert to lowercase
        $slug = strtolower($string);
    
        // Replace non-alphanumeric characters with hyphens
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    
        // Trim hyphens from the beginning and end
        $slug = trim($slug, '-');
    
        return $slug;
    }

}
