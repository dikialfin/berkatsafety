<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Support\Facades\Cache;

class CategoriesController extends Controller
{

    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = new Categories();
        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }
        if($search){
            $data = $data->where(function($query) use($search){
                $query->where('name','like','%'.$search.'%');
            });
        }
        $data = $data->with('children')->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');
        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }

    public function select(Request $request, $id = null)
    {
        if ($id) {
            $data = Categories::where('parent_id', $id)->get();
            $datas = [];
            foreach($data as $key){
                $datas[] = [
                    'id' => $key->id,
                    'text' => $key->name
                ];
            }
            return json_encode($datas);
        }
        
        $data = Categories::where('parent_id', 0)->get();
        
        $datas = [];
        foreach($data as $key){
            $datas[] = [
                'id' => $key->id,
                'text' => $key->name
            ];
        }
        return json_encode($datas);
    }

    public function selectParentChildren(Request $request)
    {
        $data = Categories::with('children')->get();

        $result = [];
        foreach ($data as $parent) {
            $group = [
                'text' => $parent->name, 
                'children' => [],        
            ];

            foreach ($parent->children as $child) {
                $group['children'][] = [
                    'id' => $child->id,  
                    'text' => $child->name,
                ];
            }

            $result[] = $group;
        }

        return response()->json($result);
    }


    public function add(Request $request)
    {
        $data = new Categories;
        $data->name = $request->name;
        $data->slug = $this->slugify($request->name);
        $data->description_id = $request->description_id;
        $data->description_en = $request->description_en;
        if($request->parent_id){
            $data->parent_id = $request->parent_id;
        }
        
        $data->logo = $request->previewImages;
        $data->keyword_id = $request->keyword_id;
        $data->keyword_en = $request->keyword_en;
        $data->meta_title_id = $request->meta_title_id;
        $data->meta_title_en = $request->meta_title_en;
        $data->meta_description_id = $request->meta_description_id;
        $data->meta_description_en = $request->meta_description_en;
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
        $data = Categories::where('id', $id)->first();

        return  response()->json([
            'data' => $data
        ]);
    }

    public function delete($id,Request $request)
    {
        $data = Categories::where('id', $id);

        if(!$data->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Categories::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete Categories {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $data = Categories::where('id', $id)->withTrashed();

        if(!$data->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Categories::where('id', $id)->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} restore Categories {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function update(Request $request)
    {
        $data = Categories::findOrFail($request->id);
        $data->name = $request->name;
        $data->slug = $this->slugify($request->name);
        $data->description_id = $request->description_id;
        $data->description_en = $request->description_en;
        $data->logo = $request->previewImages;

        if($request->banner){
            $img = $request->banner->store('berkas');
            $data->banner = url('images/'.$img);
        }

        $data->keyword_id = $request->keyword_id;
        $data->keyword_en = $request->keyword_en;
        $data->meta_title_id = $request->meta_title_id;
        $data->meta_title_en = $request->meta_title_en;
        $data->meta_description_id = $request->meta_description_id;
        $data->meta_description_en = $request->meta_description_en;

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
