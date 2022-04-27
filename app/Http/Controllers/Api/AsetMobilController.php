<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Aset_Mobil_10144;
use Illuminate\Support\Facades\DB;
use App\Models\Pemilik_Mobil_10144;

class AsetMobilController extends Controller
{
    // public function index()
    // {
    //     $aset_mobils = Aset_Mobil_10144::all(); // mengambil semua data aset_mobil
    //     // $aset_mobils = Aset_Mobil_10144::with('asetMobil_Pemilik')->get(); 

    //     if(count($aset_mobils) > 0)
    //     {
    //         return response([
    //             'message' => 'Retrieve All Success',
    //             'data' => $aset_mobils
    //         ], 200);
    //     } // return data semua aset mobil dalam bentuk json

    //     return response([
    //         'message' => 'Empty',
    //         'data' => null
    //     ], 400); // return message data aset mobil kosong
    // }

    public function index()
    {
        $aset_mobils = DB::table('aset__mobil_10144s')
                        ->leftJoin('pemilik__mobil_10144s', 'aset__mobil_10144s.id_pemilik_mobil', '=', 'pemilik__mobil_10144s.id_pemilik_mobil')
                        ->select('aset__mobil_10144s.*', 'pemilik__mobil_10144s.id_pemilik_mobil', 'pemilik__mobil_10144s.nama_pemilik_mobil', 'aset__mobil_10144s.id_pemilik_mobil')
                        ->get(); // mengambil semua data aset_mobil

        // $aset_mobils = Aset_Mobil_10144::with('asetMobil_Pemilik')->get(); 

        if(count($aset_mobils) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $aset_mobils
            ], 200);
        } // return data semua aset mobil dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data aset mobil kosong
    }


    public function show($id_aset_mobil)
    {
        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil',$id_aset_mobil)->first(); // mencari data aset berdasarkan id

        // $aset_mobil = Aset_Mobil_10144::with('asetMobil_Pemilik')->first(); 

        if(!is_null($aset_mobil))
        {
            return response([
                'message' => 'Retrieve aset_mobil Success',
                'data' => $aset_mobil
            ], 200);
        } // return data aset mobil yang ditemukan dalam bentuk json

        return response([
            'message' => 'Aset mobil Not Found',
            'data' => null
        ], 404); // return message saat data aset mobil tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'plat_nomor_mobil' => 'required|max:20',
            'nama_mobil' => 'required|max:20',
            'tipe_mobil' => 'required|max:15',
            'jenis_transmisi_mobil' => 'required|max:20',
            'jenis_bahanbakar_mobil' => 'required|max:20',
            'volume_bahanbakar_mobil' => 'required|numeric',
            'warna_mobil' => 'required|max:20',
            'kapasitas_penumpang_mobil' => 'required|numeric',
            'fasilitas_mobil' => 'required',
            'nomor_stnk_mobil' => 'numeric',
            'harga_sewa_mobil' => 'required',
            'volume_bagasi_mobil' => 'numeric'
        ]); // membuat rule validasi input
        
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        $aset_mobil = Aset_Mobil_10144::create([
            // 'id_pemilik_mobil' => $request->id_pemilik_mobil,
            'plat_nomor_mobil' => $request->plat_nomor_mobil,
            'nama_mobil' => $request->nama_mobil,
            'tipe_mobil' => $request->tipe_mobil,
            'jenis_transmisi_mobil' => $request->jenis_transmisi_mobil,
            'jenis_bahanbakar_mobil' => $request->jenis_bahanbakar_mobil,
            'volume_bahanbakar_mobil' => $request->volume_bahanbakar_mobil,
            'warna_mobil' => $request->warna_mobil,
            'kapasitas_penumpang_mobil' => $request->kapasitas_penumpang_mobil,
            'fasilitas_mobil' => $request->fasilitas_mobil,
            'nomor_stnk_mobil' => $request->nomor_stnk_mobil,
            'harga_sewa_mobil' => $request->harga_sewa_mobil,
            'volume_bagasi_mobil' => $request->volume_bagasi_mobil,
        ]);

        return response([
            'message' => 'Add Aset Mobil Success',
            'data' => $aset_mobil
        ], 200); // return data aset_mobil baru dalam bentuk json
    }

    public function destroy($id_aset_mobil)
    {
        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil',$id_aset_mobil); // mencari data aset mobil berdasarkan id

        if(is_null($aset_mobil))
        {
            return response([
                'message' => 'Aset mobil Not Found',
                'data' => null
            ], 404); 
        } // return message saat data aset mobil tidak ditemukan

        if($aset_mobil->delete())
        {
            return response([
                'message' => 'Delete aset mobil Success',
                'data' => $aset_mobil
            ], 200); 
        } // return message saat berhasil menghapus data aset mobil

        return response([
            'message' => 'Delete aset mobil Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data aset mobil
    }

    public function update(Request $request, $id_aset_mobil)
    {
        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil',$id_aset_mobil)->first();
        if(is_null($aset_mobil))
        {
            return response([
                'message' => 'Aset Mobil Not Found',
                'data' => null
            ], 404); 
        } // return message saat data aset mobil tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'plat_nomor_mobil' => 'required|max:20',
            'id_pemilik_mobil' => 'nullable',
            'nama_mobil' => 'required|max:20',
            'tipe_mobil' => 'required|max:15',
            'jenis_transmisi_mobil' => 'required|max:20',
            'jenis_bahanbakar_mobil' => 'required|max:20',
            'volume_bahanbakar_mobil' => 'required|numeric',
            'warna_mobil' => 'required|max:20',
            'kapasitas_penumpang_mobil' => 'required|numeric',
            'fasilitas_mobil' => 'required|max:100',
            'nomor_stnk_mobil' => 'numeric',
            'harga_sewa_mobil' => 'required',
            'volume_bagasi_mobil' => 'numeric'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $aset_mobil->plat_nomor_mobil = $updateData['plat_nomor_mobil'];  
        $aset_mobil->id_pemilik_mobil = $updateData['id_pemilik_mobil'];  
        $aset_mobil->nama_mobil = $updateData['nama_mobil'];  
        $aset_mobil->tipe_mobil = $updateData['tipe_mobil'];
        $aset_mobil->jenis_transmisi_mobil = $updateData['jenis_transmisi_mobil'];
        $aset_mobil->jenis_bahanbakar_mobil = $updateData['jenis_bahanbakar_mobil'];
        $aset_mobil->volume_bahanbakar_mobil = $updateData['volume_bahanbakar_mobil'];
        $aset_mobil->warna_mobil = $updateData['warna_mobil'];
        $aset_mobil->kapasitas_penumpang_mobil = $updateData['kapasitas_penumpang_mobil'];
        $aset_mobil->fasilitas_mobil = $updateData['fasilitas_mobil'];
        $aset_mobil->nomor_stnk_mobil = $updateData['nomor_stnk_mobil'];
        $aset_mobil->harga_sewa_mobil = $updateData['harga_sewa_mobil'];
        $aset_mobil->volume_bagasi_mobil = $updateData['volume_bagasi_mobil'];

        if($aset_mobil->save())
        {
            return response([
                'message' => 'Update aset mobil Success',
                'data' => $aset_mobil
            ], 200);
        } // return data aset mobil yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update aset mobil Failed',
            'data' => null
        ], 400); // return message saat aset mobil gagal diedit
    }

}
