<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\{Csr, AboutUs};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use DB;
use Illuminate\Support\Facades\Cache;

class CsrController extends Controller
{

    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $crsId = AboutUs::where('slug', 'csr')->first('id')->id;
        $data = Csr::where('csr_id', $crsId);
        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }
        if($search){
            $data = $data->where(function($query) use($search){
                $query->where('name','like','%'.$search.'%');
            });
        }
        $data = $data->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');

        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }


    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $crsId = AboutUs::where('slug', 'csr')->first('id')->id;
            $data = [
                'slug' => $request->slug,
                'name' => $request->name,
                'image' => $request->previewImages[0],
                'description_id' => $request->description_id,
                'description_en' => $request->description_en,
                'admin_id' => auth()->user()->id,
                'csr_id' => $crsId,
                'keyword_id' => $request->keyword_id ? join(',', $request->keyword_id) : '',
                'keyword_en' => $request->keyword_en ? join(',', $request->keyword_en) : '',
                'meta_title_id' => $request->meta_title_id,
                'meta_title_en' => $request->meta_title_en,
                'meta_description_id' => $request->meta_description_id,
                'meta_description_en' => $request->meta_description_en
            ];

            if ($request->id) {
                Csr::where('id', $request->id)->update($data);
            } else {
                Csr::create($data);
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

    public function edit($id)
    {
        $data = Csr::where('id', $id)->first();
        $data->keyword_id = $data->keyword_id ? explode(',', $data->keyword_id) : '';
        $data->keyword_en = $data->keyword_en ? explode(',', $data->keyword_en) : '';
        return  response()->json([
            'data' => $data
        ]);
    }

    public function delete($id,Request $request)
    {
        $data = Csr::where('id', $id);

        if(!$data->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Csr::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete CSR {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $product = Csr::withTrashed()->find($id);
        if (!$product) {
            return response()->json([
                'status' => 0,
                'message' => 'Blogs tidak ditemukan.'
            ]);
        }

        $product->restore();

        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} mengembalikan CSR {$product->name}";
        $user_log->save();

        return response()->json([
            'status' => 1,
            'message' => 'Blogs berhasil dikembalikan.'
        ]);
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
