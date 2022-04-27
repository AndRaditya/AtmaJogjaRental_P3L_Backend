<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Jadwal_Pegawai_10144;

class JadwalPegawaiController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal_Pegawai_10144::all(); // mengambil semua data jadwals

        if(count($jadwals) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jadwals
            ], 200);
        } // return data semua jadwals dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data jadwals kosong
    }

    public function show($id_jadwal_increment)
    {
        $jadwal = Jadwal_Pegawai_10144::where('id_jadwal_increment',$id_jadwal_increment)->first(); // mencari data jadwal berdasarkan id

        if(!is_null($jadwal))
        {
            return response([
                'message' => 'Retrieve jadwal Success',
                'data' => $jadwal
            ], 200);
        } // return data jadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Jadwal Not Found',
            'data' => null
        ], 404); // return message saat data jadwal tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            // 'id_jadwal' => 'required|max:50',
            'hari_shift' => 'required|max:20',
            'waktu_shift' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
            
        $hari =  $request->hari_shift;
        $waktu = $request->waktu_shift;
        
        if($waktu === '08:00:00'){
            $id = $hari . " - 1";
            $storeData['id_jadwal'] = $id;
        }else if($waktu === '15:00:00'){
            $id = $hari . " - 2";
            $storeData['id_jadwal'] = $id;
        }
        
        $jadwal = Jadwal_Pegawai_10144::create($storeData);
        return response([
            'message' => 'Add jadwal Success',
            'data' => $jadwal
        ], 200); // return data jadwal baru dalam bentuk json
    }

    public function destroy($id_jadwal_increment)
    {
        $jadwal = Jadwal_Pegawai_10144::where('id_jadwal_increment',$id_jadwal_increment); // mencari data jadwal berdasarkan id

        if(is_null($jadwal))
        {
            return response([
                'message' => 'jadwal Not Found',
                'data' => null
            ], 404); 
        } // return message saat data jadwal tidak ditemukan

        if($jadwal->delete())
        {
            return response([
                'message' => 'Delete jadwal Success',
                'data' => $jadwal
            ], 200); 
        } // return message saat berhasil menghapus data jadwal

        return response([
            'message' => 'Delete jadwal Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data jadwal
    }

    public function update(Request $request, $id_jadwal_increment)
    {
        $jadwal = Jadwal_Pegawai_10144::where('id_jadwal_increment',$id_jadwal_increment)->first();
        if(is_null($jadwal))
        {
            return response([
                'message' => 'jadwal Not Found',
                'data' => null
            ], 404); 
        } // return message saat data jadwal tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'hari_shift' => 'required|max:20',
            'waktu_shift' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $jadwal->hari_shift = $updateData['hari_shift']; 
        $jadwal->waktu_shift = $updateData['waktu_shift']; 

        if($jadwal->save())
        {
            return response([
                'message' => 'Update jadwal Success',
                'data' => $jadwal
            ], 200);
        } // return data jadwal yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update jadwal Failed',
            'data' => null
        ], 400); // return message saat jadwal gagal diedit
    }
}
