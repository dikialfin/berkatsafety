<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\Brands;
use App\Models\Translations;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Facades\Cache;

class TranslationsController extends Controller
{

    public function data( Request $request )
    {

        $data = new Translations();
        $data = $data->where('group',$request->group);
        $data = $data->orderBy('id', 'asc');
        $data = $data->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function store( Request $request )
    {
        DB::beginTransaction();
        try {
            //delete existing translations 
            Translations::where('group',$request->group)->delete();
            $insert = [];
            foreach($request->data as $val){
                $insert[] = [
                    'group' => $request->group,
                    'key' => $val['key'],
                    'translations' => json_encode($val['translations'])
                ];
            }
            Translations::insert($insert);
            $data = Translations::where('group',$request->group)->get();
            DB::commit();
            Cache::flush();
            return response()->json([
                'status' => 200,
                'data' => $data,
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
                'message' => 'Database error occurred: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function select($id, Request $request)
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
        $data = new Brands;
        $data->name = $request->name;
        $data->slug = $this->slugify($request->name);
        $data->description_id = $request->description_id;
        $data->description_en = $request->description_en;
        if($request->logo){
            $img = $request->logo->store('berkas');
            $data->logo = url('images/'.$img);
        }

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
        $data->save();

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create brand {$request->name}";
        $user_log->save();

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
        $data = Brands::findOrFail($request->id);
        $data->name = $request->name;
        $data->slug = $this->slugify($request->name);
        $data->description_id = $request->description_id;
        $data->description_en = $request->description_en;
        if($request->logo){
            $img = $request->logo->store('berkas');
            $data->logo = url('images/'.$img);
        }

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
