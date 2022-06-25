<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo_10144;
use App\Models\Transaksi_Mobil_10144;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    // public function index()
    // {
    //     $transaksis = Transaksi_Mobil_10144::all(); // mengambil semua data transaksis
    //     // $transaksis = Transaksi_Mobil_10144::with(['Transaksi_Promo','Transaksi_Customer',
    //     // 'Transaksi_Pegawai'])->get();

    //     if(count($transaksis) > 0)
    //     {
    //         return response([
    //             'message' => 'Retrieve All Success',
    //             'data' => $transaksis
    //         ], 200);
    //     } // return data semua transaksis dalam bentuk json

    //     return response([
    //         'message' => 'Empty',
    //         'data' => null
    //     ], 400); // return message data transaksis kosong
    // }

    public function index()
    {
        $transaksis = DB::table('transaksi__mobil_10144s')
            ->join('promo_10144s', 'transaksi__mobil_10144s.id_promo', '=', 'promo_10144s.id_promo')
            ->join('pegawai_10144s', 'transaksi__mobil_10144s.id_pegawai', '=', 'pegawai_10144s.id_pegawai')
            ->join('customer_10144s', 'transaksi__mobil_10144s.id_customer_increment', '=', 'customer_10144s.id_customer_increment')
            ->select('transaksi__mobil_10144s.*', 'pegawai_10144s.nama_pegawai', 'promo_10144s.id_promo',
                'promo_10144s.kode_promo', 'promo_10144s.status_promo', 'promo_10144s.diskon',
                'customer_10144s.nama_customer', 'customer_10144s.id_customer', 'customer_10144s.*')
            ->get(); // mengambil semua data aset_mobil

        if (count($transaksis) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksis,
            ], 200);
        } // return data semua transaksis dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null,
        ], 400); // return message data transaksis kosong
    }

    public function show($id_transaksi_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $id_transaksi_increment)->first(); // mencari data transaksi berdasarkan id
        // $transaksi = Transaksi_Mobil_10144::with(['Transaksi_Promo','Transaksi_Customer',
        // 'Transaksi_Pegawai'])->first();

        if (!is_null($transaksi)) {
            return response([
                'message' => 'Retrieve transaksi Success',
                'data' => $transaksi,
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'transaksi Not Found',
            'data' => null,
        ], 404); // return message saat data transaksi tidak ditemukan
    }

    public function transaksiShowByCustomerId($id_customer_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_customer_increment', $id_customer_increment)->get(); // mencari data transaksi berdasarkan id

        if (!is_null($transaksi)) {
            return response([
                'message' => 'Retrieve transaksi Success',
                'data' => $transaksi,
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'transaksi Not Found',
            'data' => null,
        ], 404); // return message saat data transaksi tidak ditemukan
    }

    public function transaksiShowByCustomer($id_customer_increment)
    {
        $transaksi = DB::table('transaksi__mobil_10144s')
            ->select('transaksi__mobil_10144s.*', 'pegawai_10144s.nama_pegawai', 'promo_10144s.id_promo',
                'promo_10144s.kode_promo', 'promo_10144s.status_promo', 'promo_10144s.diskon',
                'customer_10144s.nama_customer', 'customer_10144s.id_customer')
            ->join('promo_10144s', 'transaksi__mobil_10144s.id_promo', '=', 'promo_10144s.id_promo')
            ->join('pegawai_10144s', 'transaksi__mobil_10144s.id_pegawai', '=', 'pegawai_10144s.id_pegawai')
            ->join('customer_10144s', 'transaksi__mobil_10144s.id_customer_increment', '=', 'customer_10144s.id_customer_increment')
            ->where('transaksi__mobil_10144s.id_customer_increment', $id_customer_increment)
            ->get();

        // $transaksi = Transaksi_Mobil_10144::where('id_customer_increment',$id_customer_increment)->get(); // mencari data transaksi berdasarkan id

        if (!is_null($transaksi)) {
            return response([
                'message' => 'Retrieve transaksi Success',
                'data' => $transaksi,
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'transaksi Not Found',
            'data' => null,
        ], 404); // return message saat data transaksi tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        $storeData['tanggal_transaksi'] = Carbon::parse($storeData['tanggal_transaksi'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($storeData, [
            'tanggal_transaksi' => 'required',
            'metode_pembayaran' => 'required|max:20',
            'id_customer_increment' => 'required',
            // 'id_pegawai' => 'required',
            'id_promo' => 'nullable',
        ]); // membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // return error invalid input

        if (empty($request->id_promo)) {
            // $statusPromo = null;
            $tempId_promo = null;
        } else if (!empty($request->id_promo)) {
            $cariId_promo = Promo_10144::where('id_promo', $request->id_promo)->first();
            $statusPromo = $cariId_promo->status_promo;
            if ($statusPromo === 'Aktif') {
                $tempId_promo = $request->id_promo;
            } else {
                $tempId_promo = null;
            }
        }
        $checkTransaksi = Transaksi_Mobil_10144::count();

        //generate id transaksi
        if ($checkTransaksi == 0) {
            $id_awal = "NOTA" . date('ymd') . "00-";
            $id_transaksi_format = $id_awal . sprintf('%03d', 1);
        } else {
            $id_awal = "NOTA" . date('ymd') . "00-";
            $id_angka = DB::table('transaksi__mobil_10144s')->select('id_transaksi_increment')->orderBy('id_transaksi_increment', 'desc')->first();
            $id_angka_temp = $id_angka->id_transaksi_increment + 1;
            $id_transaksi_format = $id_awal . sprintf('%03d', $id_angka_temp);
        }

        $transaksi = Transaksi_Mobil_10144::create([
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'metode_pembayaran' => $request->metode_pembayaran,
            'id_promo' => $request->id_promo,
            'id_transaksi_mobil' => $id_transaksi_format,
            'id_customer_increment' => $request->id_customer_increment,
            'id_pegawai' => $request->id_pegawai,
        ]);
        return response([
            'message' => 'Add transaksi Success',
            'data' => $transaksi,
        ], 200); // return data transaksi baru dalam bentuk json
    }

    public function destroy($id_transaksi_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $id_transaksi_increment); // mencari data transaksi berdasarkan id

        if (is_null($transaksi)) {
            return response([
                'message' => 'transaksi Not Found',
                'data' => null,
            ], 404);
        } // return message saat data transaksi tidak ditemukan

        if ($transaksi->delete()) {
            return response([
                'message' => 'Delete transaksi Success',
                'data' => $transaksi,
            ], 200);
        } // return message saat berhasil menghapus data transaksi

        return response([
            'message' => 'Delete transaksi Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data transaksi
    }

    public function updateFromPegawai(Request $request, $id_transaksi_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $id_transaksi_increment)->first();

        if (is_null($transaksi)) {
            return response([
                'message' => 'transaksi Not Found',
                'data' => null,
            ], 404);
        } // return message saat data transaksi tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client

        $transaksi->tanggal_transaksi = Carbon::parse($updateData['tanggal_transaksi'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($updateData, [
            'tanggal_transaksi' => 'required',
            'metode_pembayaran' => 'required|max:20',
            'id_customer_increment' => 'required',
            'id_pegawai' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // return error invalid input

        $transaksi->tanggal_transaksi = $updateData['tanggal_transaksi'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        $transaksi->id_customer_increment = $updateData['id_customer_increment'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];

        if ($transaksi->save()) {
            return response([
                'message' => 'Update transaksi Success',
                'data' => $transaksi,
            ], 200);
        } // return data transaksi yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update transaksi Failed',
            'data' => null,
        ], 400); // return message saat transaksi gagal diedit
    }

    public function updateFromCustomer(Request $request, $id_transaksi_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $id_transaksi_increment)->first();
        if (is_null($transaksi)) {
            return response([
                'message' => 'transaksi Not Found',
                'data' => null,
            ], 404);
        } // return message saat data transaksi tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $transaksi->tanggal_transaksi = Carbon::parse($updateData['tanggal_transaksi'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($updateData, [
            'tanggal_transaksi' => 'required',
            'metode_pembayaran' => 'required|max:20',
            'id_customer_increment' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // return error invalid input

        $transaksi->tanggal_transaksi = $updateData['tanggal_transaksi'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        $transaksi->id_customer_increment = $updateData['id_customer_increment'];

        if ($transaksi->save()) {
            return response([
                'message' => 'Update transaksi Success',
                'data' => $transaksi,
            ], 200);
        } // return data transaksi yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update transaksi Failed',
            'data' => null,
        ], 400); // return message saat transaksi gagal diedit
    }
}