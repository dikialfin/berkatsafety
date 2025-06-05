<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\{Setting};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\UserLog;
use DB;

class SettingController extends Controller
{

    public function show()
    {
        $data = Setting::first();
        return  response()->json([
            'data' => $data
        ]);
    }

    public function add(Request $request)
    {
        DB::beginTransaction();
        try {
            $setting = Setting::updateOrCreate(
                [],
                [
                    'make_a_request' => $request->make_a_request,
                    'ask_our_admin' => $request->ask_our_admin,
                ]
            );

            
            DB::commit();
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

}
