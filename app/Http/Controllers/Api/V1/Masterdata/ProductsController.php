<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\{Brands, Products, ProductCategory, ProductMedia, ProductBrand};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use DB;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{

    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = new Products();
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
                'brands:name',
                'productMedia' => function ($query) {
                    $query->orderBy('type', 'desc');
                },
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
        DB::beginTransaction();
        try {
            $data = [
                'code' => $request->code,
                'slug' => $request->slug,
                'name' => $request->name,
                'admin_id' => auth()->user()->id,
                'description_id' => $request->description_id,
                'description_en' => $request->description_en,
                'keyword_id' => $request->keyword_id ? join(',', $request->keyword_id) : '',
                'keyword_en' => $request->keyword_en ? join(',', $request->keyword_en) : '',
                'meta_title_id' => $request->meta_title_id,
                'meta_title_en' => $request->meta_title_en,
                'meta_description_id' => $request->meta_description_id,
                'meta_description_en' => $request->meta_description_en,
                'specification_id' => $request->specification_id,
                'specification_en' => $request->specification_en
            ];

            if ($request->id) {
                Products::where('id', $request->id)->update($data);
                $productId = $request->id;
            } else {
                $product = Products::create($data);
                $productId = $product->id;
            }

            // create category
            ProductCategory::where('product_id', $productId)->delete();
            foreach($request->category_id as $val) {
                ProductCategory::insert([
                    'product_id' => $productId,
                    'category_id' => $val,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // create category
            ProductBrand::where('product_id', $productId)->delete();
            foreach($request->brand_id as $val) {
                ProductBrand::insert([
                    'product_id' => $productId,
                    'brand_id' => $val,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // create attachment
            ProductMedia::where('product_id', $productId)->delete();
            foreach($request->productUrl as $key => $val) {
                ProductMedia::insert([
                    'product_id' => $productId,
                    'type' => $request->productTypeFile[$key],
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
        $data = Products::with([
            'categories:id', 
            'brands:id',
            'productMedia'
        ])->where('id', $id)->first();
        $data->keyword_id = $data->keyword_id ? explode(',', $data->keyword_id) : '';
        $data->keyword_en = $data->keyword_en ? explode(',', $data->keyword_en) : '';
        if ($data->productMedia) {
            foreach($data->productMedia as $val) {
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
        $data = Products::where('id', $id);

        if(!$data->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Products::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete Products {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $product = Products::withTrashed()->find($id);
        if (!$product) {
            return response()->json([
                'status' => 0,
                'message' => 'Produk tidak ditemukan.'
            ]);
        }

        $product->restore();

        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} mengembalikan produk {$product->name}";
        $user_log->save();

        return response()->json([
            'status' => 1,
            'message' => 'Produk berhasil dikembalikan.'
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
