<?php

namespace App\Http\Controllers\Api\V1\Masterdata;

use App\Http\Controllers\BaseController;
use App\Models\Customer;
use App\Models\NotarisOrder;
use App\Models\Ppat;
use App\Models\PpatCustomer;
use App\Models\PpatDocument;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Role;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PpatController extends BaseController
{
    public function dashboard( Request $request )
    {
        $notaris = NotarisOrder::where('company_id',$request->user()->company_id)->get()->count();
        $ppat_progress = Ppat::where('company_id',$request->user()->company_id)->where('status',0)->get()->count();
        $ppat_selesai = Ppat::where('company_id',$request->user()->company_id)->where('status',1)->get()->count();

        return response()->json([
            'data' => [
                'total_notaris' => $notaris,
                'total_ppat_progress' => $ppat_progress,
                'total_ppat_selesai' => $ppat_selesai
            ],
        ]);
    }
    public function data( Request $request )
    {
        $size      = $request->input('size', 10);
        $search    = $request->input('search', null);
        $sortField = $request->input('sort', 'id');
        $sortAsc   = $request->input('sort_asc', false);

        $data = Ppat::with(['customers','document' => function($query){
            $query->where('doc_type','file');
        }])->whereIn('type',$request->type)
        ->where('company_id',$request->user()->company_id);

        if($request->data_status == 'archived'){
            $data = $data->onlyTrashed();
        }

        if($request->month && $request->month != 0){
            $data = $data->whereMonth('tanggal',$request->month);
        }

        if($request->status && $request->status != 'all'){
            $data = $data->where('status',$request->status);
        }

        if($search){
            $data = $data->where(function($query) use($search){
                $query->whereHas('customers', function($query) use($search){
                    $query->where('name','like','%'.$search.'%');
                });
                $query->orWhere('judul','like','%'.$search.'%');
            });
        }

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

    public function customer(Request $request)
    {
        $data = Customer::get();

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
        $data = new Ppat();
        $data->company_id = $request->user()->company_id;
        $data->user_id = $request->user()->id;
        $data->type = $request->type;
        $data->nomor = $request->nomor;
        $data->tanggal = $request->tanggal;
        $data->judul = $request->judul;
        $data->keterangan = $request->keterangan;
        $data->status = $request->status;
        $data->tahap = $request->tahap;
        $data->biaya_transaksi = $request->biaya_transaksi;
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
            $notarisCustomer = new PpatCustomer();
            $notarisCustomer->ppat_id = $data->id;
            $notarisCustomer->customer_id = $val['id'];
            $notarisCustomer->keterangan = $val['keterangan'];
            $notarisCustomer->save();
        }
        if(isset($request->data)){
            foreach($request->data as $val){
                foreach($val['berkas'] as $doc){
                    $document = new PpatDocument();
                    $document->ppat_id = $data->id;
                    $document->type = $val['type'];
                    $document->name = $doc['name'];
                    $document->doc_type = $doc['doc_type'];
                    $value = "";
                    if($doc['doc_type'] == 'file'){
                        $source = $doc['file']->store('berkas');
                        $value = url('images/'.$source);
                    }else{
                        $value = $doc['value'];
                    }
                    $document->value = $value;
                    $document->keterangan = $doc['keterangan'];
                    $document->save();
                }
            }
        }
        

        //create log
        $user_log = new UserLog();
        $user_log->user_id = $request->user()->id;
        $user_log->log = "{$request->user()->name} create order ppat {$request->type}";
        $user_log->save();

        $data = [
            'status' => 1,
        ];
        return $data;
    }

    public function edit($id)
    {
        $data = Ppat::with('customer.user','document')->where('id', $id)->first();

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
        $Role = Ppat::where('id', $id);

        if(!$Role->delete()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $Role = Ppat::where('id', $id)->withTrashed()->first();
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
        $Role = Ppat::where('id', $id)->withTrashed();

        if(!$Role->restore()){
            $data = [
                'status' => 0
            ];
            return $data;
        }else{
            $Role = Ppat::where('id', $id)->withTrashed()->first();
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
        $data = Ppat::where('id',$request->id)->first();
        $data->user_id = $request->user()->id;
        $data->type = $request->type;
        $data->nomor = $request->nomor;
        $data->tanggal = $request->tanggal;
        $data->judul = $request->judul;
        $data->keterangan = $request->keterangan;
        $data->status = $request->status;
        $data->tahap = $request->tahap;
        $data->biaya_transaksi = $request->biaya_transaksi;
        $data->save();

        $del = PpatCustomer::where('ppat_id',$request->id)->get();
        if(count($del)){
            PpatCustomer::where('ppat_id',$request->id)->delete();
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
            $notarisCustomer = new PpatCustomer();
            $notarisCustomer->ppat_id = $data->id;
            $notarisCustomer->customer_id = $val['id'];
            $notarisCustomer->keterangan = $val['keterangan'];
            $notarisCustomer->save();
        }

        $del = PpatDocument::where('ppat_id',$request->id)->get();
        if(count($del)){
            PpatDocument::where('ppat_id',$request->id)->delete();
        }

        if(isset($request->data)){
            foreach($request->data as $val){
                foreach($val['berkas'] as $doc){
                    $document = new PpatDocument();
                    $document->ppat_id = $data->id;
                    $document->type = $val['type'];
                    $document->name = $doc['name'];
                    $document->doc_type = $doc['doc_type'];
                    $value = "";
                    if($doc['doc_type'] == 'file'){
                        if(isset($doc['file']) && $doc['file'] && $doc['file'] != 'undefined' && $doc['file'] != 'null'){
                            $source = $doc['file']->store('berkas');
                            $value = url('images/'.$source);
                        }else{
                            $value = $doc['value'];
                        }
                    }else{
                        $value = $doc['value'];
                    }
                    $document->value = $value;
                    $document->keterangan = $doc['keterangan'] && $doc['keterangan'] != 'null' && $doc['keterangan'] != 'undefined'?$doc['keterangan']:'-';
                    $document->save();
                }
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
