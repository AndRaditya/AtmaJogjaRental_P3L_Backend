<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Detail_Jadwal_10144;
use App\Models\Jadwal_Pegawai_10144;
use App\Models\Pegawai_10144;
use Illuminate\Support\Facades\DB;

class DetailJadwalController extends Controller
{
    public function index()
    {
        $detail_jadwals = DB::table('detail__jadwal_10144s')
                        ->join('pegawai_10144s', 'detail__jadwal_10144s.id_pegawai', '=', 'pegawai_10144s.id_pegawai')
                        ->join('jadwal__pegawai_10144s', 'detail__jadwal_10144s.id_jadwal_increment', '=', 'jadwal__pegawai_10144s.id_jadwal_increment')
                        ->select('detail__jadwal_10144s.*', 'pegawai_10144s.*', 'jadwal__pegawai_10144s.*')
                        ->get(); // mengambil semua data aset_mobil
                        
        if(count($detail_jadwals) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detail_jadwals
            ], 200);
        } // return data semua details dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data details kosong
    }

    public function show($id_detail_jadwal)
    {
        $detail_jadwal = Detail_Jadwal_10144::where('id_detail_jadwal',$id_detail_jadwal)->first(); // mencari data detail berdasarkan id

        if(!is_null($detail_jadwal))
        {
            return response([
                'message' => 'Retrieve detail jadwal Success',
                'data' => $detail_jadwal
            ], 200);
        } // return data detail_jadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Detail jadwal Not Found',
            'data' => null
        ], 404); // return message saat data detail_jadwal tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'keterangan_jadwal' => 'required|max:255',
            'id_pegawai' => 'required',
            'id_jadwal_increment' => 'required'
        ]); // membuat rule validasi input

        $cariIDJadwal =  Detail_Jadwal_10144::where('id_jadwal_increment',$request->id_jadwal_increment)
                        ->where('id_pegawai',$request->id_pegawai)->first();

        if($validate->fails())
        return response(['message' => $validate->errors()], 400); // return error invalid input
    
        if($cariIDJadwal){
            return response([
                'message' => 'Jadwal Sudah Terambil',
                'data' => null
            ], 404); // return data detail baru dalam bentuk json
        }
        
        $detail_jadwal = Detail_Jadwal_10144::create([
            'id_pegawai' => $request->id_pegawai,
            'id_jadwal_increment' => $request->id_jadwal_increment,
            'keterangan_jadwal' => $request->keterangan_jadwal
        ]);
        return response([
            'message' => 'Add detail jadwal Success',
            'data' => $detail_jadwal
        ], 200); // return data detail baru dalam bentuk json

    }

    public function destroy($id_detail_jadwal)
    {
        $detail_jadwal = Detail_Jadwal_10144::where('id_detail_jadwal',$id_detail_jadwal); // mencari data detail berdasarkan id

        if(is_null($detail_jadwal))
        {
            return response([
                'message' => 'detail jadwal Not Found',
                'data' => null
            ], 404); 
        } // return message saat data detail tidak ditemukan

        if($detail_jadwal->delete())
        {
            return response([
                'message' => 'Delete detail Success',
                'data' => $detail_jadwal
            ], 200); 
        } // return message saat berhasil menghapus data detail

        return response([
            'message' => 'Delete detail jadwal Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data detail
    }

    public function update(Request $request, $id_detail_jadwal)
    {
        $detail_jadwal = Detail_Jadwal_10144::where('id_detail_jadwal',$id_detail_jadwal)->first();
        if(is_null($detail_jadwal))
        {
            return response([
                'message' => 'detail Not Found',
                'data' => null
            ], 404); 
        } // return message saat data detail tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'keterangan_jadwal' => 'required|max:255',
            'id_pegawai' => 'required',
            'id_jadwal_increment' => 'required'
        ]); // membuat rule validasi input

        $cariIDJadwal =  Detail_Jadwal_10144::where('id_jadwal_increment', $request->id_jadwal_increment)
                    ->where('id_pegawai', $request->id_pegawai)->first();

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        if($cariIDJadwal){
            return response([
                'message' => 'Jadwal Sudah Terambil',
                'data' => null
            ], 404); // return data detail baru dalam bentuk json
        }
        
        $detail_jadwal->keterangan_jadwal = $updateData['keterangan_jadwal'];  
        $detail_jadwal->id_pegawai = $updateData['id_pegawai'];  
        $detail_jadwal->id_jadwal_increment = $updateData['id_jadwal_increment'];  

        if($detail_jadwal->save())
        {
            return response([
                'message' => 'Update detail Success',
                'data' => $detail_jadwal
            ], 200);
        } // return data detail yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update detail Failed',
            'data' => null
        ], 400); // return message saat detail gagal diedit
    }
}
