<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Http\Controllers\BaseController;
use App\Models\Customer;
use App\Models\NotarisDocument;
use App\Models\NotarisOrder;
use App\Models\NotarisOrderCustomer;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\SubscriptionPermission;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotarisController extends BaseController
{
    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = NotarisOrder::with('customer','document');

        if($request->type){
            $data = $data->where('type',$request->type);
        }

        if($request->month && $request->month != 0){
            if($request->type == 'daftar_akta'){
                $data = $data->whereMonth('evidence_date',$request->month);
            }
            if($request->type == 'dibukukan'){
                $data = $data->whereMonth('registered_date',$request->month);
            }
            if($request->type == 'disahkan'){
                $data = $data->whereMonth('evidence_date',$request->month);
            }
        }

        if($request->status && $request->status != 'all'){
            $data = $data->where('status',$request->status);
        }

        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }

        if($search){
            $data = $data->where(function($query) use($search){
                $query->whereHas('customer', function($query) use($search){
                    $query->where('name','like','%'.$search.'%');
                });
                $query->orWhere('name','like','%'.$search.'%');
            });
        }

        $data = $data->where('company_id',$request->user()->company_id);
        $data = $data->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');
        $data = $data->paginate($size);

        return response()->json([
            'data' => $data,
            'pagination' => true,
        ]);
    }

    public function export( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = NotarisOrder::with('customer');

        if($request->type){
            $data = $data->where('type',$request->type);
        }

        if($request->month){
            if($request->type == 'daftar_akta'){
                $data = $data->whereMonth('evidence_date',$request->month);
            }
            if($request->type == 'dibukukan'){
                $data = $data->whereMonth('registered_date',$request->month);
            }
            if($request->type == 'disahkan'){
                $data = $data->whereMonth('evidence_date',$request->month);
            }
        }

        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }

        if($search){
            $data = $data->where(function($query) use($search){
                $query->where('name','like','%'.$search.'%');
            });
        }

        $data = $data->where('company_id',$request->user()->company_id);
        $data = $data->orderBy($sortField ? $sortField : 'id', $sortAsc ? 'asc' : 'desc');
        $data = $data->get();
        $parsedData = [];
        if($request->type == 'daftar_akta'){
            foreach($data as $val){
                foreach($val->customer as $idx => $cust){
                    if($idx == 0){
                        $parsedData[] = [
                            'Nomor Urut' => $val->queue_no,
                            'Nomor Bulanan/Akta' => $val->evidence_no,
                            'Tanggal Akta' => $val->evidence_date,
                            'Sifat Akta' => $val->name,
                            'Nama Penghadap dan/atau yang Diwakili/Kuasa' => $cust->name,
                            'Pekerjaan' => $cust->job
                        ];
                    }else{
                        $parsedData[] = [
                            'Nomor Urut' => "",
                            'Nomor Bulanan/Akta' => "",
                            'Tanggal Akta' => "",
                            'Sifat Akta' => "",
                            'Nama Penghadap dan/atau yang Diwakili/Kuasa' => $cust->name,
                            'Pekerjaan' => $cust->job
                        ];
                    }
                }
            }
        }

        if($request->type == 'dibukukan'){
            foreach($data as $val){
                foreach($val->customer as $idx => $cust){
                    if($idx == 0){
                        $parsedData[] = [
                            'Nomor Urut' => $val->queue_no,
                            'Tanggal Surat' => $val->evidence_date,
                            'Tanggal Didaftarkan' => $val->registred_date,
                            'Nama yang Menandatangani dan/atau yang Diwakili/Kuasa' => $cust->name,
                            'Sifat Surat' => $val->name,
                            'Pekerjaan' => $cust->job
                        ];
                    }else{
                        $parsedData[] = [
                            'Nomor Urut' => '',
                            'Tanggal Surat' => '',
                            'Tanggal Didaftarkan' => '',
                            'Nama yang Menandatangani dan/atau yang Diwakili/Kuasa' => $cust->name,
                            'Sifat Surat' => '',
                            'Pekerjaan' => $cust->job
                        ];
                    }
                }
            }
        }

        if($request->type == 'disahkan'){
            foreach($data as $val){
                foreach($val->customer as $idx => $cust){
                    if($idx == 0){
                        $parsedData[] = [
                            'Nomor Urut' => $val->queue_no,
                            'Tanggal Surat' => $val->evidence_date,
                            'Sifat Surat' => $val->name,
                            'Nama yang Menandatangani dan/atau yang Diwakili/Kuasa' => $cust->name,
                            'Pekerjaan' => $cust->job
                        ];
                    }else{
                        $parsedData[] = [
                            'Nomor Urut' => '',
                            'Tanggal Surat' => '',
                            'Sifat Surat' => '',
                            'Nama yang Menandatangani dan/atau yang Diwakili/Kuasa' => $cust->name,
                            'Pekerjaan' => $cust->job
                        ];
                    }
                }
            }
        }

        $sheet[] = [
            'name' => 'Data',
            'data' => $parsedData
        ];

        $sheet_name = $request->type;
        $sheet_name = str_replace('_', ' ', $sheet_name);
        $sheet_name = ucwords($sheet_name);

        $month = Carbon::now()->format('M');
        $year = Carbon::now()->format('Y');

        return response()->json([
            'sheet' => $sheet,
            'sheet_name' => "Laporan Bulanan Buku $sheet_name $month $year"
        ]);
    }


    public function permission( Request $request )
    {
        $department_id = $request->department_id;
        $data = Permission::get();
        $return = [];
        foreach($data as $val){
            if($val->group == 'feature'){
                $return[$val->name] = [];
            }
        }
        foreach($data as $val){
            if($val->group != 'feature'){
                $return[$val->group][] = $val;
            }
        }

        return response()->json([
            'data' => $return,
        ]);
    }

    public function select(Request $request)
    {
        $data = Subscription::get();

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

    public function customer(Request $request)
    {
        $data = Customer::where('company_id',$request->user()->company_id)->get();

        $datas = [];
        foreach($data as $key){
            $datas[] = [
                'id' => $key->name,
                'text' => $key->name,
                'cust_id' => $key->id,
                'job' => $key->job
            ];
        }
        return json_encode($datas);
    }

    public function add(Request $request)
    {
        $data = new NotarisOrder();
        $data->admin_id = $request->user()->id;
        $data->company_id = $request->user()->company_id;
        $data->type = $request->type;
        $data->queue_no = $request->queue_no;
        $data->evidence_no = $request->evidence_no;
        $data->evidence_date = $request->evidence_date;
        $data->registered_date = $request->registered_date;
        $data->name = $request->name;
        $data->biaya = $request->biaya;
        $data->status = $request->status;
        $data->keterangan = $request->keterangan;
        $data->save();

        foreach($request->customer as $val){
            if($val['id'] == 0){
                $customer = new Customer();
                $customer->company_id = $request->user()->company_id;
                $customer->admin_id = $request->user()->id;
                $customer->name = $val['name'];
                $customer->job = $val['job'];
                $customer->save();

                $val['id'] = $customer->id;
            }
            $notarisCustomer = new NotarisOrderCustomer();
            $notarisCustomer->notaris_order_id = $data->id;
            $notarisCustomer->customer_id = $val['id'];
            $notarisCustomer->save();
        }

        if(isset($request->document)){
            foreach($request->document as $val){
                $notarisCustomer = new NotarisDocument();
                $notarisCustomer->notaris_order_id = $data->id;
                $notarisCustomer->name = $val['name'];
                $source = $val['file']->store('berkas');
                $notarisCustomer->source = url('images/'.$source);
                
                $notarisCustomer->save();
            }
        }

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create order notaris {$request->type}";
        $user_log->save();

        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function edit($id)
    {
        $data = NotarisOrder::with('customer','document')->where('id', $id)->first();

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
        $Role = NotarisOrder::where('id', $id);

        if(!$Role->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $Role = NotarisOrder::where('id', $id)->withTrashed()->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} delete notaris order {$Role->type} - {$Role->queue_no} - {$Role->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function restore($id, Request $request)
    {
        $Role = NotarisOrder::where('id', $id)->withTrashed();

        if(!$Role->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $Role = NotarisOrder::where('id', $id)->withTrashed()->first();
            //create log
            $user_log = new UserLog();
            $user_log->user_id = $request->user()->id;
            $user_log->log = "{$request->user()->name} restore notaris order {$Role->name}";
            $user_log->save();

            $data = [
                'status' => 1
            ];
            return $data;
        }

    }

    public function update(Request $request)
    { 
        $data = NotarisOrder::where('id',$request->id)->first();
        $data->admin_id = $request->user()->id;
        $data->company_id = $request->user()->company_id;
        $data->type = $request->type;
        $data->queue_no = $request->queue_no;
        $data->evidence_no = $request->evidence_no;
        $data->evidence_date = $request->evidence_date;
        $data->registered_date = $request->registered_date;
        $data->name = $request->name;
        $data->biaya = $request->biaya;
        $data->status = $request->status;
        $data->keterangan = $request->keterangan;
        $data->save();

        $del = NotarisOrderCustomer::where('notaris_order_id',$request->id)->get();
        if(count($del)){
            NotarisOrderCustomer::where('notaris_order_id',$request->id)->delete();
        }

        foreach($request->customer as $val){
            if($val['id'] == 0){
                $customer = new Customer();
                $customer->company_id = $request->user()->company_id;
                $customer->admin_id = $request->user()->id;
                $customer->name = $val['name'];
                $customer->job = $val['job'];
                $customer->save();

                $val['id'] = $customer->id;
            }
            $notarisCustomer = new NotarisOrderCustomer();
            $notarisCustomer->notaris_order_id = $data->id;
            $notarisCustomer->customer_id = $val['id'];
            $notarisCustomer->save();
        }

        $del = NotarisDocument::where('notaris_order_id',$request->id)->get();
        if(count($del)){
            NotarisDocument::where('notaris_order_id',$request->id)->delete();
        }

        if(isset($request->document)){
            foreach($request->document as $val){
                $notarisCustomer = new NotarisDocument();
                $notarisCustomer->notaris_order_id = $data->id;
                $notarisCustomer->name = $val['name'];
                if($val['source'] != null && $val['source'] != "" && $val['source'] != "null"){
                    $notarisCustomer->source = $val['source'];
                }else{
                    $source = $val['file']->store('berkas');
                    $notarisCustomer->source = url('images/'.$source);
                }
                $notarisCustomer->save();
            }
        }
        
        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} update order notaris {$request->type}";
        $user_log->save();

        $data = [
            'status' => 1,
            'data' => $data
        ];
        return $data;
    }
    
}
