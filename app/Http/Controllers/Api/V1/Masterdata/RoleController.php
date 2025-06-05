<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserLog;

class RoleController extends Controller
{
    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = new Role();
        $data = $data->where('is_show','=',1);
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

    public function permission( Request $request )
    {
        $data = Permission::get();
        $return = [];
        foreach($data as $val){
            $return[$val->group][] = $val->name;
        }

        return response()->json([
            'data' => $return,
        ]);
    }

    public function select(Request $request)
    {
        $data = Role::where('is_default','!=',1)->get();

        $datas = [];
        if($request->filter){
            $datas[] = [
                'id' => 0,
                'text' => 'All Roles'
            ];
        }
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
        $Role = new Role;

        $Role->name = $request->name;
        $Role->save();

        if($request->permissions){
            foreach($request->permissions as $val){
                $permission = new RolePermission();
                $permission->permission = $val;
                $permission->role_id = $Role->id;
                $permission->save();
            }
        }

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create role {$Role->name}";
        $user_log->save();

        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function edit($id)
    {
        $data = Role::where('id', $id)->first();
        $permission = RolePermission::where('role_id',$data->id)->get()->pluck('permission');
        $data->permissions = $permission;

        return  response()->json([
            'data' => $data
        ]);
    }

    public function active($id)
    {
        $Role = Role::where('id', $id);

        if(!$Role->update(['status' => 1])){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = [
                'status' => 1
            ];
            return $data;
        }
    }

    public function deactive($id)
    {
        $Role = Role::where('id', $id);

        if(!$Role->update(['status' => 0])){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $data = [
                'status' => 1
            ];
            return $data;
        }
    }

    public function delete($id,Request $request)
    {
        $Role = Role::where('id', $id);

        if(!$Role->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $Role = Role::where('id', $id)->withTrashed()->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete role {$Role->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $Role = Role::where('id', $id)->withTrashed();

        if(!$Role->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $Role = Role::where('id', $id)->withTrashed()->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} restore role {$Role->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function update(Request $request)
    {

        $Role = Role::findOrFail($request->id);

        $Role->name = $request->name;

        $deletepermission = RolePermission::where('role_id',$request->id)->delete();

        foreach($request->permissions as $val){
            $permission = new RolePermission();
            $permission->permission = $val;
            $permission->role_id = $request->id;
            $permission->save();
        }
        if(!$Role->update()){
            $data = [
                'status' => 0,
            ];
            return $data;
        }else{
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} update role {$Role->name}";
            $user_log->save();

            $data = [
                'status' => 1,
                'data' => $Role
            ];
            return $data;
        }
    }
    
}
