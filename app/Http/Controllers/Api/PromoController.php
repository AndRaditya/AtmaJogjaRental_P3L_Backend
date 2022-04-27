<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Promo_10144;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo_10144::all(); // mengambil semua data promos

        if(count($promos) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $promos
            ], 200);
        } // return data semua promos dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data promos kosong
    }

    public function show($id_promo)
    {
        // $promo = Promo_10144::find($id); // mencari data promo berdasarkan id
        $promo = Promo_10144::where('id_promo', $id_promo)->first();

        if(!is_null($promo))
        {
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        } // return data promo yang ditemukan dalam bentuk json

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 404); // return message saat data promo tidak ditemukan
    }

    public function statusPromo()
    {
        // $promo = Promo_10144::find($id); // mencari data promo berdasarkan id
        $caristatus_promo = 'Aktif';
        $promo = Promo_10144::where('status_promo', $caristatus_promo)->get();

        if(!is_null($promo))
        {
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        } // return data promo yang ditemukan dalam bentuk json

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 404); // return message saat data promo tidak ditemukan
    }

    public function statusPromoAll()
    {
        // $promo = Promo_10144::find($id); // mencari data promo berdasarkan id
        $cariIdPromo = '8';
        $promo = Promo_10144::whereNotIn('id_promo', [$cariIdPromo])->get();

        if(!is_null($promo))
        {
            return response([
                'message' => 'Retrieve Promo Success',
                'data' => $promo
            ], 200);
        } // return data promo yang ditemukan dalam bentuk json

        return response([
            'message' => 'Promo Not Found',
            'data' => null
        ], 404); // return message saat data promo tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client

        $validate = Validator::make($storeData, [
            'kode_promo' => 'required|max:10',
            'jenis_promo' => 'required|max:20',
            'keterangan_promo' => 'required|max:200',
            'diskon' => 'required|numeric',
            'status_promo' => 'required|max:20'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        $promo = Promo_10144::create($storeData);
        return response([
            'message' => 'Add promo Success',
            'data' => $promo
        ], 200); // return data promo baru dalam bentuk json
    }

    public function destroy($id_promo)
    {
        // $promo = Promo_10144::find($id); // mencari data promo berdasarkan id
        $promo = Promo_10144::where('id_promo',$id_promo);

        if(is_null($promo))
        {
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404); 
        } // return message saat data promo tidak ditemukan

        if($promo->delete())
        {
            return response([
                'message' => 'Delete promo Success',
                'data' => $promo
            ], 200); 
        } // return message saat berhasil menghapus data promo

        return response([
            'message' => 'Delete promo Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data promo
    }

    public function update(Request $request, $id_promo)
    {
        $promo = Promo_10144::where('id_promo',$id_promo)->first();
        if(is_null($promo))
        {
            return response([
                'message' => 'Promo Not Found',
                'data' => null
            ], 404); 
        } // return message saat data promo tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'kode_promo' => 'required|max:10',
            'jenis_promo' => 'required|max:20',
            'keterangan_promo' => 'required|max:200',
            'diskon' => 'required|numeric',
            'status_promo' => 'required|max:20'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $promo->kode_promo = $updateData['kode_promo'];  
        $promo->jenis_promo = $updateData['jenis_promo'];  
        $promo->keterangan_promo = $updateData['keterangan_promo'];
        $promo->diskon = $updateData['diskon'];
        $promo->status_promo = $updateData['status_promo'];

        if($promo->save())
        {
            return response([
                'message' => 'Update promo Success',
                'data' => $promo
            ], 200);
        } // return data promo yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update promo Failed',
            'data' => null
        ], 400); // return message saat promo gagal diedit
    }

}
