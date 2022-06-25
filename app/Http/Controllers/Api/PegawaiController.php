<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Pegawai_10144;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;;

class PegawaiController extends Controller
{
    //
    // public function index()
    // {
    //     $pegawais = Pegawai_10144::with('Pegawai_Jabatan')->get(); 

    //     if(count($pegawais) > 0)
    //     {
    //         return response([
    //             'message' => 'Retrieve All Success',
    //             'data' => $pegawais
    //         ], 200);
    //     } // return data semua pegawais dalam bentuk json

    //     return response([
    //         'message' => 'Empty',
    //         'data' => null
    //     ], 400); // return message data pegawais kosong
    // }

    public function index()
    {
        $pegawais = DB::table('pegawai_10144s')
                    ->join('jabatan__pegawai_10144s', 'pegawai_10144s.id_jabatan', '=', 'jabatan__pegawai_10144s.id_jabatan')
                    ->select('pegawai_10144s.*', 'jabatan__pegawai_10144s.id_jabatan', 'jabatan__pegawai_10144s.nama_jabatan', 'pegawai_10144s.id_jabatan')
                    ->get(); // mengambil semua data aset_mobil

        if(count($pegawais) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $pegawais
            ], 200);
        } // return data semua pegawais dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data pegawais kosong
    }

    public function show($id_pegawai)
    {
        $pegawai = Pegawai_10144::where('id_pegawai',$id_pegawai)->first(); // mencari data pegawai berdasarkan id

        if(!is_null($pegawai))
        {
            return response([
                'message' => 'Retrieve pegawai Success',
                'data' => $pegawai
            ], 200);
        } // return data pegawai yang ditemukan dalam bentuk json

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404); // return message saat data pegawai tidak ditemukan
    }

    public function cariPegawai_Shift()
    {
        $pegawai = DB::table('pegawai_10144s')
                    ->leftJoin('detail__jadwal_10144s', 'detail__jadwal_10144s.id_pegawai', '=', 'pegawai_10144s.id_pegawai')
                    ->leftJoin('jadwal__pegawai_10144s', 'jadwal__pegawai_10144s.id_jadwal_increment', '=', 'detail__jadwal_10144s.id_jadwal_increment')
                    ->select('pegawai_10144s.id_pegawai', 'pegawai_10144s.nama_pegawai', 'pegawai_10144s.alamat_pegawai')
                    ->groupBy('pegawai_10144s.id_pegawai')
                    ->whereNotIn('pegawai_10144s.id_pegawai', [6])
                    ->having(DB::raw('count(detail__jadwal_10144s.id_pegawai)'), '<', 6)
                    ->get();
                    // ->count();

        if(!is_null($pegawai))
        {
            return response([
                'message' => 'Retrieve pegawai Success',
                'data' => $pegawai
            ], 200);
        } // return data detail_jadwal yang ditemukan dalam bentuk json

        return response([
            'message' => 'Pegawai Not Found',
            'data' => null
        ], 404); // return message saat data detail_jadwal tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_pegawai' => 'required|max:100',
            'nomor_telepon_pegawai' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_pegawai' => 'required|max:200',
            'email_pegawai' => 'required|email:rfc,dns|unique:pegawai_10144s',
            'tanggal_lahir_pegawai' => 'required|date_format:Y-m-d',
            'foto_pegawai' => 'max:1024|mimes:jpg,png,jpeg|image',
            'jenis_kelamin_pegawai' => 'required|max:10',
            'password_pegawai' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        $uploadFotoPegawai= $request->foto_pegawai->store('img_pegawai', ['disk' => 'public']);
        $passwordHash = Hash::make($request->password_pegawai);

        $pegawai = Pegawai_10144::create([
            'id_jabatan' => $request->id_jabatan,
            'nama_pegawai' => $request->nama_pegawai,
            'nomor_telepon_pegawai' => $request->nomor_telepon_pegawai,
            'alamat_pegawai' => $request->alamat_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'tanggal_lahir_pegawai' => $request->tanggal_lahir_pegawai,
            'foto_pegawai' => $uploadFotoPegawai,
            'jenis_kelamin_pegawai' => $request->jenis_kelamin_pegawai,
            'password_pegawai' => $passwordHash
        ]);
        
        return response([
            'message' => 'Add pegawai Success',
            'data' => $pegawai
        ], 200); // return data pegawai baru dalam bentuk json
    }

    public function destroy($id_pegawai)
    {
        $pegawai = Pegawai_10144::where('id_pegawai',$id_pegawai); // mencari data pegawai berdasarkan id

        if(is_null($pegawai))
        {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404); 
        } // return message saat data pegawai tidak ditemukan

        if($pegawai->delete())
        {
            return response([
                'message' => 'Delete pegawai Success',
                'data' => $pegawai
            ], 200); 
        } // return message saat berhasil menghapus data pegawai

        return response([
            'message' => 'Delete pegawai Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data pegawai
    }

    public function update(Request $request, $id_pegawai)
    {
        $pegawai = Pegawai_10144::where('id_pegawai',$id_pegawai)->first();
        if(is_null($pegawai))
        {
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404); 
        } // return message saat data pegawai tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_pegawai' => 'required|max:100',
            'nomor_telepon_pegawai' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_pegawai' => 'required|max:200',
            'email_pegawai' => 'required',
            'tanggal_lahir_pegawai' => 'required|date_format:Y-m-d',
            'foto_pegawai' => 'mimes:jpg,png,jpeg|image',
            'jenis_kelamin_pegawai' => 'required|max:10',
            'password_pegawai' => 'nullable'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input


        if(isset($request->password_pegawai)){
            $updateData['password_pegawai'] = bcrypt($request->password_pegawai);
            $pegawai->password_pegawai = $updateData['password_pegawai'];
        }

        $pegawai->nama_pegawai = $updateData['nama_pegawai'];  
        $pegawai->nomor_telepon_pegawai = $updateData['nomor_telepon_pegawai'];  
        $pegawai->alamat_pegawai = $updateData['alamat_pegawai'];
        $pegawai->email_pegawai = $updateData['email_pegawai'];
        $pegawai->tanggal_lahir_pegawai = $updateData['tanggal_lahir_pegawai'];
        if(isset($request->foto_pegawai)){
            $uploadFotoPegawai = $request->foto_pegawai->store('img_pegawai', ['disk' => 'public']);
            $pegawai->foto_pegawai = $uploadFotoPegawai;
        }
        $pegawai->jenis_kelamin_pegawai = $updateData['jenis_kelamin_pegawai'];
        // $pegawai->password_pegawai = $passwordHash;

        if($pegawai->save())
        {
            return response([
                'message' => 'Update pegawai Success',
                'data' => $pegawai
            ], 200);
        } // return data pegawai yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update pegawai Failed',
            'data' => null
        ], 400); // return message saat pegawai gagal diedit
    }

}