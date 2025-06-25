<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnnouncementController extends Controller
{
    public function data(Request $request)
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = new Announcement();
        if ($request->data_status == 'archived') {
            $data = $data->onlyTrashed();
        }
        if ($search) {
            $data = $data->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
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
        // dd($request);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'image' => $request->previewImages[0],
                'description_id' => $request->description_id,
                'description_en' => $request->description_en,
                'admin_id' => auth()->user()->id
            ];

            if ($request->id) {
                Announcement::where('id', $request->id)->update($data);
                $blogId = $request->id;
            } else {
                $blog = Announcement::create($data);
                $blogId = $blog->id;
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
        $data = Announcement::where('id', $id)->first();

        return  response()->json([
            'data' => $data
        ]);
    }

    public function delete($id,Request $request)
    {
        $data = Announcement::where('id', $id);

        if(!$data->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = Announcement::where('id', $id)->withTrashed()->first();
             //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete Announcement {$data->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $announcement = Announcement::withTrashed()->find($id);

        // dd($announcement);

        if (!$announcement) {
            return response()->json([
                'status' => 0,
                'message' => 'Blogs tidak ditemukan.'
            ]);
        }

        $announcement->restore();

        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} mengembalikan Announcement {$announcement->name}";
        $user_log->save();

        return response()->json([
            'status' => 1,
            'message' => 'Blogs berhasil dikembalikan.'
        ]);
    }
}
