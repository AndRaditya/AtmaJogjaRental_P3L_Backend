<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Jabatan_Pegawai_10144;

class JabatanPegawaiController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan_Pegawai_10144::all(); // mengambil semua data jabatans

        if(count($jabatans) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jabatans
            ], 200);
        } // return data semua jabatans dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data jabatans kosong
    }

    public function show($id_jabatan)
    {
        $jabatan = Jabatan_Pegawai_10144::where('id_jabatan',$id_jabatan)->first(); // mencari data jabatan berdasarkan id

        if(!is_null($jabatan))
        {
            return response([
                'message' => 'Retrieve jabatan Success',
                'data' => $jabatan
            ], 200);
        } // return data jabatan yang ditemukan dalam bentuk json

        return response([
            'message' => 'Jabatan Not Found',
            'data' => null
        ], 404); // return message saat data jabatan tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_jabatan' => 'required|max:20'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        $jabatan = Jabatan_Pegawai_10144::create($storeData);
        return response([
            'message' => 'Add jabatan Success',
            'data' => $jabatan
        ], 200); // return data jabatan baru dalam bentuk json
    }

    public function destroy($id_jabatan)
    {
        $jabatan = Jabatan_Pegawai_10144::where('id_jabatan',$id_jabatan); // mencari data jabatan berdasarkan id

        if(is_null($jabatan))
        {
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ], 404); 
        } // return message saat data jabatan tidak ditemukan

        if($jabatan->delete())
        {
            return response([
                'message' => 'Delete jabatan Success',
                'data' => $jabatan
            ], 200); 
        } // return message saat berhasil menghapus data jabatan

        return response([
            'message' => 'Delete jabatan Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data jabatan
    }

    public function update(Request $request, $id_jabatan)
    {
        $jabatan = Jabatan_Pegawai_10144::where('id_jabatan',$id_jabatan)->first();
        if(is_null($jabatan))
        {
            return response([
                'message' => 'Jabatan Not Found',
                'data' => null
            ], 404); 
        } // return message saat data jabatan tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_jabatan' => 'required|max:20'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $jabatan->nama_jabatan = $updateData['nama_jabatan'];  

        if($jabatan->save())
        {
            return response([
                'message' => 'Update jabatan Success',
                'data' => $jabatan
            ], 200);
        } // return data jabatan yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update jabatan Failed',
            'data' => null
        ], 400); // return message saat jabatan gagal diedit
    }
    
}
