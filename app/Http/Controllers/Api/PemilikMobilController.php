<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Pemilik_Mobil_10144;

class PemilikMobilController extends Controller
{
    public function index()
    {
        $pemiliks = Pemilik_Mobil_10144::all(); // mengambil semua data pemilik
        // $pemiliks = Pemilik_Mobil_10144::with('asetMobil_Pemilik')->get(); 

        if(count($pemiliks) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pemiliks
            ], 200);
        } // return data semua pemilik dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data pemilik kosong
    }

    public function namaPemilik()
    {
        // $promo = Promo_10144::find($id); // mencari data promo berdasarkan id
        $cariIdPemilik = 6;
        $pemilik = Pemilik_Mobil_10144::whereNotIn('id_pemilik_mobil', [$cariIdPemilik])->get();

        if(!is_null($pemilik))
        {
            return response([
                'message' => 'Retrieve Pemilik Success',
                'data' => $pemilik
            ], 200);
        } // return data pemilik yang ditemukan dalam bentuk json

        return response([
            'message' => 'Pemilik Not Found',
            'data' => null
        ], 404); // return message saat data pemilik tidak ditemukan
    }

    
    public function show($id_pemilik_mobil)
    {
        $pemilik = Pemilik_Mobil_10144::where('id_pemilik_mobil',$id_pemilik_mobil)->first(); // mencari data pemilik berdasarkan id

        if(!is_null($pemilik))
        {
            return response([
                'message' => 'Retrieve Pemilik Success',
                'data' => $pemilik
            ], 200);
        } // return data pemilik yang ditemukan dalam bentuk json

        return response([
            'message' => 'Pemilik Not Found',
            'data' => null
        ], 404); // return message saat data pemilik tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_pemilik_mobil' => 'required|max:50',
            'no_ktp_pemilik_mobil' => 'required|numeric|digits:16',
            'alamat_pemilik_mobil' => 'required',
            'nomor_telepon_pemilik_mobil' => 'required|numeric|digits_between:10,13|starts_with:08',
            'periode_kontrak_mulai_mobil' => 'required|date_format:Y-m-d',
            'periode_kontrak_akhir_mobil' => 'required|date_format:Y-m-d|after:periode_kontrak_mulai_mobil',
            'tanggal_terakhir_servis_mobil' => 'required|date_format:Y-m-d'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        $pemilik = Pemilik_Mobil_10144::create($storeData);
        return response([
            'message' => 'Add Pemilik Success',
            'data' => $pemilik
        ], 200); // return data pemilik baru dalam bentuk json
    }

    public function destroy($id_pemilik_mobil)
    {
        $pemilik = Pemilik_Mobil_10144::where('id_pemilik_mobil',$id_pemilik_mobil); // mencari data Pemilik berdasarkan id

        if(is_null($pemilik))
        {
            return response([
                'message' => 'Pemilik Not Found',
                'data' => null
            ], 404); 
        } // return message saat data pemilik tidak ditemukan

        if($pemilik->delete())
        {
            return response([
                'message' => 'Delete pemilik Success',
                'data' => $pemilik
            ], 200); 
        } // return message saat berhasil menghapus data pemilik

        return response([
            'message' => 'Delete pemilik Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data pemilik
    }

    public function update(Request $request, $id_pemilik_mobil)
    {
        $pemilik = Pemilik_Mobil_10144::where('id_pemilik_mobil',$id_pemilik_mobil)->first();
        if(is_null($pemilik))
        {
            return response([
                'message' => 'Pemilik Not Found',
                'data' => null
            ], 404); 
        } // return message saat data pemilik tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_pemilik_mobil' => 'required|max:50|regex:/^[\pL\s\-]+$/u',
            'no_ktp_pemilik_mobil' => 'required|numeric|digits:16',
            'alamat_pemilik_mobil' => 'required',
            'nomor_telepon_pemilik_mobil' => 'required|numeric|digits_between:10,13|starts_with:08',
            'periode_kontrak_mulai_mobil' => 'required',
            'periode_kontrak_akhir_mobil' => 'required',
            'tanggal_terakhir_servis_mobil' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $pemilik->nama_pemilik_mobil = $updateData['nama_pemilik_mobil'];  
        $pemilik->no_ktp_pemilik_mobil = $updateData['no_ktp_pemilik_mobil'];
        $pemilik->alamat_pemilik_mobil = $updateData['alamat_pemilik_mobil'];
        $pemilik->nomor_telepon_pemilik_mobil = $updateData['nomor_telepon_pemilik_mobil'];
        $pemilik->periode_kontrak_mulai_mobil = $updateData['periode_kontrak_mulai_mobil'];
        $pemilik->periode_kontrak_akhir_mobil = $updateData['periode_kontrak_akhir_mobil'];
        $pemilik->tanggal_terakhir_servis_mobil = $updateData['tanggal_terakhir_servis_mobil'];

        if($pemilik->save())
        {
            return response([
                'message' => 'Update pemilik Success',
                'data' => $pemilik
            ], 200);
        } // return data pemilik yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update pemilik Failed',
            'data' => null
        ], 400); // return message saat pemilik gagal diedit
    }
}
