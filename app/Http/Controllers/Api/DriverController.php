<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Driver_10144;
use Illuminate\Support\Facades\DB;
use Hash;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver_10144::all(); // mengambil semua data drivers

        if(count($drivers) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $drivers
            ], 200);
        } // return data semua drivers dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data drivers kosong
    }
    
    public function show($id_driver_increment)
    {
        $driver = Driver_10144::where('id_driver_increment',$id_driver_increment)->first(); // mencari data driver berdasarkan id

        if(!is_null($driver))
        {
            return response([
                'message' => 'Retrieve driver Success',
                'data' => $driver
            ], 200);
        } // return data driver yang ditemukan dalam bentuk json

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404); // return message saat data driver tidak ditemukan
    }

    public function cariDriver()
    {
        $driver = Driver_10144::whereNotIn('id_driver_increment',[7])->get(); // mencari data driver berdasarkan id

        if(!is_null($driver))
        {
            return response([
                'message' => 'Retrieve driver Success',
                'data' => $driver
            ], 200);
        } // return data driver yang ditemukan dalam bentuk json

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404); // return message saat data driver tidak ditemukan
    }

    public function cariDriverSibuk()
    {
        $driver = Driver_10144::where('status_driver',['Tersedia'])->get(); // mencari data driver berdasarkan id

        if(!is_null($driver))
        {
            return response([
                'message' => 'Retrieve driver Success',
                'data' => $driver
            ], 200);
        } // return data driver yang ditemukan dalam bentuk json

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ], 404); // return message saat data driver tidak ditemukan
    }
    
    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_driver' => 'required|max:50',
            'nomor_telepon_driver' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_driver' => 'required|max:200',
            'email_driver' => 'required|email:rfc,dns|unique:driver_10144s',
            'tanggal_lahir_driver' => 'required|date_format:Y-m-d',
            'jenis_kelamin_driver' => 'required|max:10',
            'bahasa_driver' => 'required',
            'foto_driver' => 'max:1024|mimes:jpg,png,jpeg|image',
            'tarif_driver_harian' => 'required|numeric',
            'password_driver' => 'required',
            'status_driver' => 'required',
            'foto_sim' => 'max:1024|mimes:jpg,png,jpeg|image',
            'surat_napza' => 'max:1024|mimes:jpg,png,jpeg|image',
            'surat_kesehatan_jiwa' => 'max:1024|mimes:jpg,png,jpeg|image',
            'surat_kesehatan_jasmani' => 'max:1024|mimes:jpg,png,jpeg|image',
            'skck' => 'max:1024|mimes:jpg,png,jpeg|image'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
            
        $passwordHash = Hash::make($request->password_driver);
        $checkDriver = Driver_10144::count();
        
        //generate id driver
        if($checkDriver == 0){
            $id_awal = "DRV-" . date('dmy');
            $id_driver_format = $id_awal . sprintf('%03d', 1); 
            // $storeData['id_driver']=$id_driver_format;
        }else{
            $id_awal = "DRV-" . date('dmy');
            $id_angka = DB::table('driver_10144s')->select('id_driver_increment')->orderBy('id_driver_increment','desc')->first();
            $id_angka_temp = $id_angka->id_driver_increment+1;
            $id_driver_format = $id_awal . sprintf('%03d', $id_angka_temp); 
            // $storeData['id_driver']=$id_driver_format;
        }
        $uploadFotoDriver = $request->foto_driver->store('img_driver', ['disk' => 'public']);
        $uploadSIM = $request->foto_sim->store('img_SIM_driver', ['disk' => 'public']);
        $uploadSuratNapza = $request->surat_napza->store('img_napza_driver', ['disk' => 'public']);
        $uploadSuratKesehatanJiwa = $request->surat_kesehatan_jiwa->store('img_suratJiwa_driver', ['disk' => 'public']);
        $uploadSuratKesehatanJasmani = $request->surat_kesehatan_jasmani->store('img_suratJasmani_driver', ['disk' => 'public']);
        $uploadSKCK = $request->skck->store('img_SKCK_driver', ['disk' => 'public']);

        // $storeData['rerata_rating'] = 0;  
        // $storeData['status_driver'] = 'Tersedia'; 
        $rating = 0;
        $status = 'Tersedia';
        
        $driver = Driver_10144::create([
            'id_driver' => $id_driver_format,
            'rerata_rating' => $rating,
            'status_driver' => $request->status_driver,
            'nama_driver' => $request->nama_driver,
            'nomor_telepon_driver' => $request->nomor_telepon_driver,
            'alamat_driver' => $request->alamat_driver,
            'email_driver' => $request->email_driver,
            'tanggal_lahir_driver' => $request->tanggal_lahir_driver,
            'jenis_kelamin_driver' => $request->jenis_kelamin_driver,
            'bahasa_driver' => $request->bahasa_driver,
            'tarif_driver_harian' => $request->tarif_driver_harian,
            'password_driver' => $passwordHash,
            'foto_driver' => $uploadFotoDriver,
            'foto_sim' => $uploadSIM,
            'surat_napza' => $uploadSuratNapza,
            'surat_kesehatan_jiwa' => $uploadSuratKesehatanJiwa,
            'surat_kesehatan_jasmani' => $uploadSuratKesehatanJasmani,
            'skck' => $uploadSKCK,
        ]);
        return response([
            'message' => 'Add driver Success',
            'data' => $driver
        ], 200); // return data driver baru dalam bentuk json
    }

    public function destroy($id_driver_increment)
    {
        $driver = Driver_10144::where('id_driver_increment',$id_driver_increment); // mencari data driver berdasarkan id

        if(is_null($driver))
        {
            return response([
                'message' => 'driver Not Found',
                'data' => null
            ], 404); 
        } // return message saat data driver tidak ditemukan

        if($driver->delete())
        {
            return response([
                'message' => 'Delete driver Success',
                'data' => $driver
            ], 200); 
        } // return message saat berhasil menghapus data driver

        return response([
            'message' => 'Delete driver Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data driver
    }

    public function update(Request $request, $id_driver_increment)
    {
        $driver = Driver_10144::where('id_driver_increment',$id_driver_increment)->first();
        if(is_null($driver))
        {
            return response([
                'message' => 'driver Not Found',
                'data' => null
            ], 404); 
        } // return message saat data driver tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        // return $request->all();
        
        $validate = Validator::make($updateData, [
            'nama_driver' => 'required',
            'nomor_telepon_driver' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_driver' => 'required',
            'email_driver' => ['required|email:rfc,dns', Rule::unique('driver_10144s')->ignore($driver)],
            'tanggal_lahir_driver' => 'required|date_format:Y-m-d',
            'jenis_kelamin_driver' => 'required',
            'bahasa_driver' => 'required',
            'status_driver' => 'required',
            'foto_driver' => 'mimes:jpg,png,jpeg|image',
            'foto_sim' => 'mimes:jpg,png,jpeg|image',
            'surat_napza' => 'mimes:jpg,png,jpeg|image',
            'surat_kesehatan_jiwa' => 'mimes:jpg,png,jpeg|image',
            'surat_kesehatan_jasmani' => 'mimes:jpg,png,jpeg|image',
            'skck' => 'mimes:jpg,png,jpeg|image',
            // 'foto_driver' => 'required',
            'tarif_driver_harian' => 'required|numeric',
            'password_driver' => 'required',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $passwordHash = Hash::make($request->password_driver);

        $driver->nama_driver = $updateData['nama_driver'];  
        $driver->nomor_telepon_driver = $updateData['nomor_telepon_driver'];  
        $driver->alamat_driver = $updateData['alamat_driver'];
        $driver->email_driver = $updateData['email_driver'];
        $driver->tanggal_lahir_driver = $updateData['tanggal_lahir_driver'];  
        $driver->jenis_kelamin_driver = $updateData['jenis_kelamin_driver'];  
        $driver->bahasa_driver = $updateData['bahasa_driver'];
        if(isset($request->foto_driver)){
            $uploadFotoDriver = $request->foto_driver->store('img_driver', ['disk' => 'public']);
            $driver->foto_driver = $uploadFotoDriver;
        }
        if(isset($request->surat_napza)){
            $uploadSuratNapza = $request->surat_napza->store('img_napza_driver', ['disk' => 'public']);
            $driver->surat_napza = $uploadSuratNapza;
        }
        if(isset($request->foto_sim)){
            $uploadSIM = $request->foto_sim->store('img_SIM_driver', ['disk' => 'public']);
            $driver->foto_sim = $uploadSIM;
        }
        if(isset($request->surat_kesehatan_jasmani)){
            $uploadSuratKesehatanJasmani = $request->surat_kesehatan_jasmani->store('img_suratJasmani_driver', ['disk' => 'public']);
            $driver->surat_kesehatan_jasmani = $uploadSuratKesehatanJasmani;
        }
        if(isset($request->surat_kesehatan_jiwa)){
            $uploadSuratKesehatanJiwa = $request->surat_kesehatan_jiwa->store('img_suratJiwa_driver', ['disk' => 'public']);
            $driver->surat_kesehatan_jiwa = $uploadSuratKesehatanJiwa;
        }
        if(isset($request->skck)){
            $uploadSKCK = $request->skck->store('img_SKCK_driver', ['disk' => 'public']);
            $driver->skck = $uploadSKCK;
        }

        // $driver->foto_driver = $updateData['foto_driver'];
        $driver->tarif_driver_harian = $updateData['tarif_driver_harian'];  
        $driver->status_driver = $updateData['status_driver'];  
        $driver->password_driver = $passwordHash;
        // $driver->rerata_rating = $updateData['rerata_rating'];


        if($driver->save())
        {
            return response([
                'message' => 'Update driver Success',
                'data' => $driver
            ], 200);
        } // return data driver yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update driver Failed',
            'data' => null
        ], 400); // return message saat driver gagal diedit
    }


    // public function update(Request $request, $id_driver_increment)
    // {
    //     $driver = Driver_10144::where('id_driver_increment',$id_driver_increment)->first();
    //     if(is_null($driver))
    //     {
    //         return response([
    //             'message' => 'Driver Not Found',
    //             'data' => null
    //         ], 404); 
    //     } // return message saat data promo tidak ditemukan

    //     $updateData = $request->all(); // mengambil semua input dari api client
    //     $validate = Validator::make($updateData, [
    //         // 'kode_promo' => 'required|max:10',
    //         // 'jenis_promo' => 'required|max:20',
    //         // 'keterangan_promo' => 'required|max:200',
    //         // 'diskon' => 'required|numeric',
    //         // 'status_promo' => 'required|max:20'
    //     ]); // membuat rule validasi input

    //     if($validate->fails())
    //         return response(['message' => $validate->errors()], 400); // return error invalid input

    //     $passwordHash = Hash::make($request->password_driver);

    //     $driver->nama_driver = $updateData['nama_driver'];  
    //     $driver->nomor_telepon_driver = $updateData['nomor_telepon_driver'];  
    //     $driver->alamat_driver = $updateData['alamat_driver'];
    //     $driver->email_driver = $updateData['email_driver'];
    //     $driver->tanggal_lahir_driver = $updateData['tanggal_lahir_driver'];  
    //     $driver->jenis_kelamin_driver = $updateData['jenis_kelamin_driver'];  
    //     $driver->bahasa_driver = $updateData['bahasa_driver'];
    //     if(isset($request->foto_driver)){
    //         $uploadFotoDriver = $request->foto_driver->store('img_driver', ['disk' => 'public']);
    //         $driver->foto_driver = $uploadFotoDriver;
    //     }
    //     // $driver->foto_driver = $updateData['foto_driver'];
    //     $driver->tarif_driver_harian = $updateData['tarif_driver_harian'];  
    //     $driver->status_driver = $updateData['status_driver'];  
    //     $driver->password_driver = $passwordHash;
    //     $driver->rerata_rating = $updateData['rerata_rating'];
    //     $driver->status_driver = $updateData['status_driver'];

    //     if($driver->save())
    //     {
    //         return response([
    //             'message' => 'Update driver Success',
    //             'data' => $driver
    //         ], 200);
    //     } // return data driver yang telah diedit dalam bentuk json
    //     return response([
    //         'message' => 'Update driver Failed',
    //         'data' => null
    //     ], 400); // return message saat driver gagal diedit
    // }

}
