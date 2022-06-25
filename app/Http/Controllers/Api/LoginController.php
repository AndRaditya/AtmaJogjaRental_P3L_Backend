<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer_10144;
use App\Models\Driver_10144;
use App\Models\Pegawai_10144;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'email_login' => 'required|email:rfc',
            'password_login' => 'required|max:20',
            'opsi_login' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // return error invalid input

        if ($storeData['opsi_login'] === 'Customer') { //login customer
            if ($cariEmail = Customer_10144::where('email_customer', '=', $storeData['email_login'])->first()) {
                if ($cariEmail->status_dokumen === 'Sudah Terverifikasi') {
                    $cekPassword = Hash::check($request->password_login, $cariEmail->password_customer);

                    if ($cekPassword) {
                        $data = Customer_10144::where('email_customer', $request->email_login)->first();
                        return response([
                            'message' => 'Login Customer Berhasil',
                            'data' => $data,
                        ], 200);
                    } else {
                        return response([
                            'message' => 'Email atau Password Customer Salah',
                            'data' => null,
                        ], 404); // return
                    }
                } else if ($cariEmail->status_dokumen === 'Menunggu verifikasi oleh Customer Service') {
                    return response([
                        'message' => 'Email Anda belum diverifikasi oleh Customer Service',
                        'data' => null,
                    ], 404); // return
                }
            } else {
                return response([
                    'message' => 'Email Customer tidak ditemukan',
                    'data' => null,
                ], 404); // return
            }
        } else if ($storeData['opsi_login'] === 'Pegawai') { //login pegawai
            if ($cariEmail = Pegawai_10144::where('email_pegawai', '=', $storeData['email_login'])->first()) {
                $cekPassword = Hash::check($request->password_login, $cariEmail->password_pegawai);

                if ($cekPassword) {
                    $data = Pegawai_10144::where('email_pegawai', $request->email_login)->first();
                    return response([
                        'message' => 'Login Pegawai Berhasil',
                        'data' => $data,
                    ], 200);
                } else {
                    return response([
                        'message' => 'Email atau Password Pegawai Salah',
                        'data' => null,
                    ], 404); // return
                }
            } else {
                return response([
                    'message' => 'Email Pegawai tidak ditemukan',
                    'data' => null,
                ], 404); // return
            }
        } else if ($storeData['opsi_login'] === 'Driver') { //login driver
            if ($cariEmail = Driver_10144::where('email_driver', '=', $storeData['email_login'])->first()) {
                $cekPassword = Hash::check($request->password_login, $cariEmail->password_driver);

                if ($cekPassword) {
                    $data = Driver_10144::where('email_driver', $request->email_login)->first();
                    return response([
                        'message' => 'Login Driver Berhasil',
                        'data' => $data,
                    ], 200);
                } else {
                    return response([
                        'message' => 'Email atau Password Driver Salah',
                        'data' => null,
                    ], 404); // return
                }
            } else {
                return response([
                    'message' => 'Email Driver tidak ditemukan',
                    'data' => null,
                ], 404); // return
            }
        }
    }
}