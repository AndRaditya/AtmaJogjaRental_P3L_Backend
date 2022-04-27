<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer_10144;
use DB;
use Hash;

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
            'no_sim_customer' => 'nullable',
            // 'status_dokumen' => 'required|max:100'
        ]); // membuat rule validasi input


        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        // $checkCustomer = Customer_10144::where('id_customer_increment');
        // $id_angka = DB::table('customer_10144s')->select('id_customer_increment')->get();
        $checkCustomer = Customer_10144::count();
        
        //generate id customer
        if($checkCustomer == 0){
            $id_awal = "CUS" . date('ymd')."-";
            $id_customer_format = $id_awal . sprintf('%03d', 1); 
            // $storeData['id_customer']=$id_customer_format;
        }else{
            $id_awal = "CUS" . date('ymd')."-";
            $id_angka = DB::table('customer_10144s')->select('id_customer_increment')->orderBy('id_customer_increment','desc')->first();
            $id_angka_temp = $id_angka->id_customer_increment+1;
            $id_customer_format = $id_awal . sprintf('%03d', $id_angka_temp); 
            // $storeData['id_customer']=$id_customer_format;
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
            'no_sim_customer' => $request->no_sim_customer,
            'id_customer' => $id_customer_format,
            'password_customer' => $passwordHash
        ]);
        return response([
            'message' => 'Add customer Success',
            'data' => $customer
        ], 200); // return data customer baru dalam bentuk json
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
            'no_sim_customer' => 'max:16',
            'password_customer' => 'nullable',
            // 'status_dokumen' => 'nullable'
        ]); // membuat rule validasi input

        $passwordHash = Hash::make($request->password_customer);

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
        $customer->no_sim_customer = $updateData['no_sim_customer'];
        $customer->password_customer = $passwordHash;

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
            'no_sim_customer' => 'required|max:16',
            'password_customer' => 'nullable',
            // 'status_dokumen' => 'nullable'
        ]); // membuat rule validasi input

        $passwordHash = Hash::make($request->password_customer);

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
        $customer->password_customer = $passwordHash;

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
