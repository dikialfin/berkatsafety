<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\{Blogs, BlogCategory, BlogsMedia};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use DB;
use Illuminate\Support\Facades\Cache;

class BlogsController extends Controller
{

    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = new Blogs();
        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }
        if($search){
            $data = $data->where(function($query) use($search){
                $query->where('name','like','%'.$search.'%');
            });
        }
        $data = $data->with(
            [
                'categories:name',
                'blogMedia:blogs_id,value'
            ]
        )->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');

        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }


    public function add(Request $request)
    {
        dd($request);
        DB::beginTransaction();
        try {
            $data = [
                'slug' => $request->slug,
                'name' => $request->name,
                'image' => $request->previewImages[0],
                'description_id' => $request->description_id,
                'description_en' => $request->description_en,
                'admin_id' => auth()->user()->id,
                'keyword_id' => $request->keyword_id ? join(',', $request->keyword_id) : '',
                'keyword_en' => $request->keyword_en ? join(',', $request->keyword_en) : '',
                'meta_title_id' => $request->meta_title_id,
                'meta_title_en' => $request->meta_title_en,
                'meta_description_id' => $request->meta_description_id,
                'meta_description_en' => $request->meta_description_en
            ];

            if ($request->id) {
                Blogs::where('id', $request->id)->update($data);
                $blogId = $request->id;
            } else {
                $blog = Blogs::create($data);
                $blogId = $blog->id;
            }

            // create category
            BlogCategory::where('blog_id', $blogId)->delete();
            foreach($request->category_id as $val) {
                BlogCategory::insert([
                    'blog_id' => $blogId,
                    'category_id' => $val,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // create attachment
            BlogsMedia::where('blogs_id', $blogId)->delete();
            foreach($request->previewImages as $key => $val) {
                BlogsMedia::insert([
                    'blogs_id' => $blogId,
                    'type' => $request->blogTypeFile[$key],
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

    public function edit($id)
    {
        $data = Blogs::with([
            'categories:id',
            'blogMedia'
        ])->where('id', $id)->first();
        $data->keyword_id = $data->keyword_id ? explode(',', $data->keyword_id) : '';
        $data->keyword_en = $data->keyword_en ? explode(',', $data->keyword_en) : '';

        if ($data->blogMedia) {
            foreach($data->blogMedia as $val) {
                $name = explode('/', $val->value);
                $name[count($name) - 1] = 'thumbs/' . $name[count($name) - 1];
                $newUrl = implode('/', $name);

                $val->name = end($name);
                $val->thumb_url = $val->type == 'image' ? $newUrl : null;
                $val->url = $val->value;
            }
        }
        return  response()->json([
            'data' => $data
        ]);
    }

    public function delete($id,Request $request)
    {
        $data = Blogs::where('id', $id);

        if(!$data->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Blogs::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete Blogs {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $product = Blogs::withTrashed()->find($id);
        if (!$product) {
            return response()->json([
                'status' => 0,
                'message' => 'Blogs tidak ditemukan.'
            ]);
        }

        $product->restore();

        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} mengembalikan blogs {$product->name}";
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
