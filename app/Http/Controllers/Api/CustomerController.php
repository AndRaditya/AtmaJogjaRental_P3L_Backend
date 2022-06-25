<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer_10144;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer_10144::all(); // mengambil semua data customers

        if(count($customers) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200);
        } // return data semua customers dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data customers kosong
    }

    public function show($id_customer_increment)
    {
        $customer = Customer_10144::where('id_customer_increment',$id_customer_increment)->first(); // mencari data customer berdasarkan id

        if(!is_null($customer))
        {
            return response([
                'message' => 'Retrieve customer Success',
                'data' => $customer
            ], 200);
        } // return data customer yang ditemukan dalam bentuk json

        return response([
            'message' => 'Customer Not Found',
            'data' => null
        ], 404); // return message saat data customer tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_customer' => 'required|max:50',
            'nomor_telepon_customer' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_customer' => 'required|max:200',
            'jenis_kelamin_customer' => 'required|max:200',
            'email_customer' => 'required|unique:customer_10144s',
            'tanggal_lahir_customer' => 'required|date_format:Y-m-d',
            'no_ktp_customer' => 'required|digits:16',
            'foto_ktp_customer' => 'mimes:jpg,png,jpeg|image',
            'foto_sim_customer' => 'mimes:jpg,png,jpeg|image',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        $tempTanggalLahir = $request->tanggal_lahir_customer;
        $tempTanggalSekarang = date('Y-m-d');
        $tempUmur = Carbon::parse($tempTanggalLahir)->diff($tempTanggalSekarang)->y;

        if($tempUmur >= 17){
            $checkCustomer = Customer_10144::count();
        
            //generate id customer
            if($checkCustomer == 0){
                $id_awal = "CUS" . date('ymd')."-";
                $id_customer_format = $id_awal . sprintf('%03d', 1); 
            }else{
                $id_awal = "CUS" . date('ymd')."-";
                $id_angka = DB::table('customer_10144s')->select('id_customer_increment')->orderBy('id_customer_increment','desc')->first();
                $id_angka_temp = $id_angka->id_customer_increment+1;
                $id_customer_format = $id_awal . sprintf('%03d', $id_angka_temp); 
            }
    
            $uploadKTP_customer = $request->foto_ktp_customer->store('img_ktp_customer', ['disk' => 'public']);
    
            if (isset($request->foto_sim_customer)) {
                $uploadSIM_customer = $request->foto_sim_customer->store('img_sim_customer', ['disk' => 'public']);
            }

            $passwordHash = Hash::make($request->tanggal_lahir_customer);
            $statusDokumen = 'Menunggu verifikasi oleh Customer Service';
    
            $customer = Customer_10144::create([
                'status_dokumen' => $statusDokumen,
                'nama_customer' => $request->nama_customer,
                'nomor_telepon_customer' => $request->nomor_telepon_customer,
                'alamat_customer' => $request->alamat_customer,
                'jenis_kelamin_customer' => $request->jenis_kelamin_customer,
                'email_customer' => $request->email_customer,
                'tanggal_lahir_customer' => $request->tanggal_lahir_customer,
                'no_ktp_customer' => $request->no_ktp_customer,
                'foto_ktp_customer' => $uploadKTP_customer,
                'foto_sim_customer' => $uploadSIM_customer,
                'id_customer' => $id_customer_format,
                'password_customer' => $passwordHash,
                'umur_customer' => $tempUmur
            ]);
            return response([
                'message' => 'Add customer Success',
                'data' => $customer
            ], 200); // return data customer baru dalam bentuk json
        }else if($tempUmur < 17){
            return response([
                'message' => 'Anda belum cukup umur',
                'data' => null,
            ], 404); 
        }else{
            return response([
                'message' => 'Not Found',
                'data' => null,
            ], 404); 
        }
    }

    public function destroy($id_customer_increment)
    {
        $customer = Customer_10144::where('id_customer_increment',$id_customer_increment); // mencari data customer berdasarkan id

        if(is_null($customer))
        {
            return response([
                'message' => 'customer Not Found',
                'data' => null
            ], 404); 
        } // return message saat data customer tidak ditemukan

        if($customer->delete())
        {
            return response([
                'message' => 'Delete customer Success',
                'data' => $customer
            ], 200); 
        } // return message saat berhasil menghapus data customer

        return response([
            'message' => 'Delete customer Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data customer
    }

    public function update(Request $request, $id_customer_increment)
    {
        $customer = Customer_10144::where('id_customer_increment',$id_customer_increment)->first();
        if(is_null($customer))
        {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404); 
        } // return message saat data customer tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_customer' => 'required|max:50',
            'nomor_telepon_customer' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_customer' => 'required|max:200',
            'jenis_kelamin_customer' => 'required|max:200',
            'email_customer' => 'required|email:rfc,dns',
            'tanggal_lahir_customer' => 'required|date_format:Y-m-d',
            'no_ktp_customer' => 'required|digits:16',
            'password_customer' => 'nullable'
            // 'no_sim_customer' => 'max:16',
            // 'status_dokumen' => 'nullable'
        ]); // membuat rule validasi input

        if(isset($request->password_customer)){
            $updateData['password_customer'] = bcrypt($request->password_customer);
            $customer->password_customer = $updateData['password_customer'];
        }
        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $customer->status_dokumen = $updateData['status_dokumen'];
        $customer->nama_customer = $updateData['nama_customer'];  
        $customer->nomor_telepon_customer = $updateData['nomor_telepon_customer'];
        $customer->alamat_customer = $updateData['alamat_customer'];
        $customer->jenis_kelamin_customer = $updateData['jenis_kelamin_customer'];
        $customer->email_customer = $updateData['email_customer'];
        $customer->tanggal_lahir_customer = $updateData['tanggal_lahir_customer'];
        $customer->no_ktp_customer = $updateData['no_ktp_customer'];
        // $customer->password_customer = $passwordHash;
        // $customer->no_sim_customer = $updateData['no_sim_customer'];

        if($customer->save())
        {
            return response([
                'message' => 'Update customer Success',
                'data' => $customer
            ], 200);
        } // return data customer yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update customer Failed',
            'data' => null
        ], 400); // return message saat customer gagal diedit
    }

    public function updateCustomerSendiri(Request $request, $id_customer_increment)
    {
        $customer = Customer_10144::where('id_customer_increment',$id_customer_increment)->first();
        if(is_null($customer))
        {
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404); 
        } // return message saat data customer tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_customer' => 'required|max:50',
            'nomor_telepon_customer' => 'required|numeric|digits_between:10,13|starts_with:08',
            'alamat_customer' => 'required|max:200',
            'jenis_kelamin_customer' => 'required|max:200',
            'email_customer' => 'required|email:rfc,dns',
            'tanggal_lahir_customer' => 'required|date_format:Y-m-d',
            'no_ktp_customer' => 'required|digits:16',
            'no_sim_customer' => 'nullable|max:16',
            'password_customer' => 'nullable',
            'foto_ktp_customer' => 'mimes:jpg,png,jpeg|image',
            'foto_sim_customer' => 'mimes:jpg,png,jpeg|image',
            // 'status_dokumen' => 'nullable'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        // $customer->status_dokumen = $updateData['status_dokumen'];
        $customer->nama_customer = $updateData['nama_customer'];  
        $customer->nomor_telepon_customer = $updateData['nomor_telepon_customer'];
        $customer->alamat_customer = $updateData['alamat_customer'];
        $customer->jenis_kelamin_customer = $updateData['jenis_kelamin_customer'];
        $customer->email_customer = $updateData['email_customer'];
        $customer->tanggal_lahir_customer = $updateData['tanggal_lahir_customer'];
        $customer->no_ktp_customer = $updateData['no_ktp_customer'];
        $customer->no_sim_customer = $updateData['no_sim_customer'];
        if(isset($request->password_customer)){
            $updateData['password_customer'] = bcrypt($request->password_customer);
            $customer->password_customer = $updateData['password_customer'];
        }
        if (isset($request->foto_ktp_customer)) {
            $uploadKTP_customer = $request->foto_ktp_customer->store('img_ktp_customer', ['disk' => 'public']);
            $customer->foto_ktp_customer = $uploadKTP_customer;
        }
        if (isset($request->foto_sim_customer)) {
            $uploadSIM_customer = $request->foto_sim_customer->store('img_sim_customer', ['disk' => 'public']);
            $customer->foto_sim_customer = $uploadSIM_customer;
        }
        // $customer->password_customer = $passwordHash;

        if($customer->save())
        {
            return response([
                'message' => 'Update customer Success',
                'data' => $customer
            ], 200);
        } // return data customer yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update customer Failed',
            'data' => null
        ], 400); // return message saat customer gagal diedit
    }
}